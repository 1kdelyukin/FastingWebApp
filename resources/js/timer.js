/**
 * Most current update:
 *  Fasting duration can be saved to the database given the current configuration.
 *  Please check the console tab on the browser to see the response from the server.
 *  Comment the log statement after debugging.
 * 
 *  Start timer and End timer button have been updated to required specifications.
 *  The timer will ask for confirmation before stopping the timer.
 *  The timer is an SVG element that changes based on the state of the timer.
 *  The timer will start when you leave/refresh the page if it was running before.
 * 
 *  Fasting duration is saved to the local storage as well and will be loaded when the page is refreshed.
 * 
 * Required Changes:
 *  - The dropdown always displays 16:00.
 *  - When the timer is running, the goal time is calculated using the fasting duration the user set
 *    before reload (stored in separate state variables).
 *  - On reload, if the timer is still running, the original start time is preserved.
 *  - "Time since last fast" is pulled from the database using the updated_at column from the last entry.
 */

document.addEventListener('DOMContentLoaded', () => {
    // State Variables
    let isRunning = false;
    let intervalId = null;
    let nextWindowChangeTime = null;
    let currentWindowStartTime = null;
    let currentWindowEndTime = null;
    let elapsedTime = 0; // Track elapsed time in milliseconds
    let lastFastEndTime = null; // Track the last fast end time

    // Tracking Actual Fasting Duration
    let totalFastingTime = 0; // In milliseconds
    let currentStartTime = null; // Timestamp when fasting started
    let timerStartTimestamp = null; // Absolute timestamp when timer was started

    // These two variables store the user-specified fasting duration when the timer is started.
    // They are used for calculating the goal time if the timer is running on reload.
    let runningFastingDurationHours = '16';
    let runningFastingDurationMinutes = '00';

    // DOM Elements
    const toggleTimer = document.getElementById('toggleTimer');
    const timerText = document.getElementById('timerText');
    const windowDescription = document.getElementById('windowDescription');
    const eatingWindowStart = document.getElementById('eatingWindowStart');
    const eatingWindowEnd = document.getElementById('eatingWindowEnd');
    const backgroundArc = document.getElementById('backgroundArc');
    const progressArc = document.getElementById('progressArc');
    const eatingWindow = document.getElementById('eatingWindow');
    const startLabel = document.getElementById('startLabel');
    const endLabel = document.getElementById('endLabel');
    const startTimeDisplay = document.getElementById('startTimeDisplay');
    const fastingDurationHours = document.getElementById('fastingDurationHours');
    const fastingDurationMinutes = document.getElementById('fastingDurationMinutes');
    const fastingDurationContainer = document.getElementById('fastingDurationContainer');
    const endTimeDisplay = document.getElementById('endTimeDisplay');
    const fastedTimeLabel = document.getElementById('fastedTime');

    // Event Listeners
    toggleTimer.addEventListener('click', toggleTimerFunction);
    eatingWindowStart.addEventListener('change', () => {
        updateEatingWindowDisplay();
        saveStateToStorage();
    });
    eatingWindowEnd.addEventListener('change', () => {
        updateEatingWindowDisplay();
        saveStateToStorage();
    });
    startTimeDisplay.addEventListener('click', () => {
        if (!isRunning) {
            updateStartTimeDisplay();
        }
    });

    // Restore timer state before initializing other UI elements.
    initializeTimerFromStorage();

    // Initialize duration dropdowns (always showing 16:00)
    initializeDurationDropdowns();

    // On page load, retrieve the last fasting record from the server.
    axios.get('/fasting/last')
        .then(response => {
            if (response.data.success && response.data.last_fasting_end) {
                // Set lastFastEndTime from the database's updated_at value
                lastFastEndTime = new Date(response.data.last_fasting_end);
                // Immediately update the "Time since last fast" display
                updateTimeSinceLastFast();
            }
        })
        .catch(error => {
            console.error('Error fetching last fasting record:', error);
        });

    // Format date in "Feb 19, 20:15" format
    function formatDateDisplay(date) {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const month = months[date.getMonth()];
        const day = date.getDate();
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${month} ${day}, ${hours}:${minutes}`;
    }

    // Update start time display
    function updateStartTimeDisplay() {
        if (isRunning && currentStartTime) {
            startTimeDisplay.textContent = formatDateDisplay(currentStartTime);
        } else {
            startTimeDisplay.textContent = formatDateDisplay(new Date());
        }
    }

    // Update the start time display immediately and every minute when not running
    updateStartTimeDisplay();
    setInterval(() => {
        if (!isRunning) {
            updateStartTimeDisplay();
        }
    }, 60000);

    // Initialize duration selectors – dropdown always shows 16:00
    function initializeDurationDropdowns() {
        // Populate hours (0-23)
        for (let i = 0; i <= 23; i++) {
            const option = document.createElement('option');
            option.value = i.toString().padStart(2, '0');
            option.textContent = i.toString().padStart(2, '0');
            fastingDurationHours.appendChild(option);
        }
        // Populate minutes (0-59) in steps of 5
        for (let i = 0; i < 60; i += 5) {
            const option = document.createElement('option');
            option.value = i.toString().padStart(2, '0');
            option.textContent = i.toString().padStart(2, '0');
            fastingDurationMinutes.appendChild(option);
        }
        // Always show 16:00 in the dropdown
        fastingDurationHours.value = '16';
        fastingDurationMinutes.value = '00';

        // Listen for changes while the timer is not running
        fastingDurationHours.addEventListener('change', updateEndTimeFromDuration);
        fastingDurationMinutes.addEventListener('change', updateEndTimeFromDuration);

        // Initial calculation from the dropdown values
        updateEndTimeFromDuration();
    }

    // Calculate end time based on current time and dropdown-selected duration.
    // Used when the timer is not running.
    function updateEndTimeFromDuration() {
        const hours = parseInt(fastingDurationHours.value, 10);
        const minutes = parseInt(fastingDurationMinutes.value, 10);
        const now = new Date();
        const endTime = new Date(now.getTime() + (hours * 60 + minutes) * 60000);
        const endHours = endTime.getHours().toString().padStart(2, '0');
        const endMinutes = endTime.getMinutes().toString().padStart(2, '0');
        eatingWindowEnd.value = `${endHours}:${endMinutes}`;
        endTimeDisplay.textContent = formatDateDisplay(endTime);
        saveStateToStorage();
    }

    // Calculate and display end time based on the stored running duration and original start time.
    function updateEndTimeDisplay() {
        if (isRunning && currentStartTime) {
            const hours = parseInt(runningFastingDurationHours, 10);
            const minutes = parseInt(runningFastingDurationMinutes, 10);
            const endTime = new Date(currentStartTime.getTime() + (hours * 60 + minutes) * 60000);
            endTimeDisplay.textContent = formatDateDisplay(endTime);
            fastingDurationContainer.classList.add('hidden');
            endTimeDisplay.classList.remove('hidden');
        } else {
            fastingDurationContainer.classList.remove('hidden');
            endTimeDisplay.classList.add('hidden');
        }
    }

    // Function to start/stop the timer.
    function toggleTimerFunction() {
        const now = new Date();
        if (isRunning) {
            // Capture current fasting state before updating UI.
            const wasFasting = currentStartTime && windowDescription.innerHTML.includes('Fasting');

            // Stop the Timer
            clearInterval(intervalId);
            toggleTimer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 45 45" fill="none"><circle cx="22.5" cy="22.5" r="22.5" fill="#3F8855"/><path d="M32.5 20.4019C34.5 21.5566 34.5 24.4434 32.5 25.5981L20.5 32.5263C18.5 33.681 16 32.2376 16 29.9282V16.0718C16 13.7624 18.5 12.319 20.5 13.4737L32.5 20.4019Z" fill="white"/></svg>';
            isRunning = false;
            const stopConfirmed = confirm('Did you stop the timer?');
            if (!stopConfirmed) {
                isRunning = true;
                intervalId = setInterval(updateCountUpTimer, 1000);
                return;
            }
            lastFastEndTime = now;
            fastedTimeLabel.textContent = "Time since last fast";
            windowDescription.innerHTML = '<div><div style="padding: 11px 40px; border-radius: 25px; background-color:rgba(237, 109, 82, 0.7)"><div style="font-size: 20px;">Feeding</div></div></div>';
            startLabel.textContent = 'START';
            startLabel.style.color = 'rgba(237, 109, 82, 0.7)';
            endLabel.style.color = 'rgba(237, 109, 82, 0.7)';
            startTimeDisplay.style.pointerEvents = 'none';
            eatingWindowStart.disabled = false;
            eatingWindowEnd.disabled = false;
            drawBackgroundArc("rgba(237, 109, 82, 0.2)");
            progressArc.setAttribute('d', '');
            const progressEl = document.getElementById('progressValue');
            if (progressEl) {
                progressEl.textContent = 'Progress 0%';
                progressEl.style.color = "rgba(237, 109, 82, 0.7)";
            }
            fastingDurationContainer.classList.remove('hidden');
            endTimeDisplay.classList.add('hidden');
            elapsedTime = 0;
            timerStartTimestamp = null;
            timerText.textContent = '00:00:00';

            // Save fasting record if we were in fasting mode
            if (wasFasting) {
                const elapsedTime = now - currentStartTime;
                totalFastingTime += elapsedTime;
                saveFastingRecord(totalFastingTime);
                currentStartTime = null;
            }
            saveStateToStorage();
            updateStartTimeDisplay();
        } else {
            const start = new Date(`${now.toDateString()} ${eatingWindowStart.value}`);
            let end = new Date(`${now.toDateString()} ${eatingWindowEnd.value}`);
            if (end <= start) end.setDate(end.getDate() + 1);
            if (now >= start && now < end) {
                nextWindowChangeTime = new Date(end);
                currentWindowStartTime = new Date(start);
                currentWindowEndTime = new Date(end);
                windowDescription.innerHTML = '<div><div style="padding: 11px 40px; border-radius: 25px; background-color:rgba(63, 136, 85, 0.7)"><div style="font-size: 20px;">Fasting</div></div></div>';
            } else {
                nextWindowChangeTime = now < start ? new Date(start) : new Date(start.setDate(start.getDate() + 1));
                currentWindowStartTime = now < start ? new Date(end.setDate(end.getDate() - 1)) : new Date(end);
                currentWindowEndTime = new Date(nextWindowChangeTime);
                currentStartTime = new Date(now);
                windowDescription.innerHTML = '<div style="font-size: 20px; padding: 10px 40px; border-radius: 25px; background-color:rgba(63, 136, 85, 0.7);">Fasting</div>';
            }
            // When starting the timer, store the current fasting duration from the dropdown.
            runningFastingDurationHours = fastingDurationHours.value;
            runningFastingDurationMinutes = fastingDurationMinutes.value;
            isRunning = true;
            fastedTimeLabel.textContent = "You’ve fasted for";
            startLabel.textContent = 'STARTED';
            startLabel.style.color = 'rgba(63, 136, 85, 0.7)';
            endLabel.style.color = 'rgba(63, 136, 85, 0.7)';
            timerStartTimestamp = now.getTime();
            // Do not update currentStartTime here if it was already set (for fasting, it is set above).
            if (!currentStartTime) {
                currentStartTime = now;
            }
            updateEatingWindowDisplay();
            intervalId = setInterval(updateCountUpTimer, 1000);
            toggleTimer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 45 45" fill="none"><circle cx="22.5" cy="22.5" r="22.5" fill="#ED6D52"/><rect x="13.5" y="13.5" width="18" height="18" rx="3" fill="white"/></svg>';
            startTimeDisplay.style.pointerEvents = 'none';
            eatingWindowStart.disabled = true;
            eatingWindowEnd.disabled = true;
            fastingDurationContainer.classList.add('hidden');
            endTimeDisplay.classList.remove('hidden');
            updateEndTimeDisplay();
            saveStateToStorage();
            updateStartTimeDisplay();
        }
        updateCountUpTimer();
    }

    // Update the count-up timer using the stored running fasting duration.
    function updateCountUpTimer() {
        if (!isRunning || !timerStartTimestamp) return;
        const now = new Date();
        const elapsedMillis = now.getTime() - timerStartTimestamp;
        const hours = Math.floor(elapsedMillis / 3600000).toString().padStart(2, '0');
        const minutes = Math.floor((elapsedMillis % 3600000) / 60000).toString().padStart(2, '0');
        const seconds = Math.floor((elapsedMillis % 60000) / 1000).toString().padStart(2, '0');
        timerText.textContent = `${hours}:${minutes}:${seconds}`;
        const progressEl = document.getElementById('progressValue');
        if (progressEl) {
            const goalHours = isRunning ? parseInt(runningFastingDurationHours, 10) : parseInt(fastingDurationHours.value, 10);
            const goalMinutes = isRunning ? parseInt(runningFastingDurationMinutes, 10) : parseInt(fastingDurationMinutes.value, 10);
            const goalDurationMs = (goalHours * 60 + goalMinutes) * 60 * 1000;
            const progressPercent = goalDurationMs > 0 ? Math.min(100, Math.round((elapsedMillis / goalDurationMs) * 100)) : 0;
            progressEl.textContent = `Progress ${progressPercent}%`;
            progressEl.style.color = "rgba(63, 136, 85, 0.7)";
            drawProgressArc(progressPercent / 100, "rgba(63, 136, 85, 1)");
        }
    }

    // Update the time since the last fast ended.
    // Uses the global variable lastFastEndTime which is now set via the database call.
    function updateTimeSinceLastFast() {
        if (!lastFastEndTime) return;
        const now = new Date();
        const diff = now - lastFastEndTime;
        const hours = Math.floor(diff / 3600000).toString().padStart(2, '0');
        const minutes = Math.floor((diff % 3600000) / 60000).toString().padStart(2, '0');
        const seconds = Math.floor((diff % 60000) / 1000).toString().padStart(2, '0');
        timerText.textContent = `${hours}:${minutes}:${seconds}`;
    }

    // Update timer display for the eating/fasting window.
    function updateTimer() {
        const now = new Date();
        const timeDiff = nextWindowChangeTime - now;
        if (timeDiff <= 0) {
            toggleTimerFunction();
            return;
        }
        const hours = Math.floor(timeDiff / 3600000).toString().padStart(2, '0');
        const minutes = Math.floor((timeDiff % 3600000) / 60000).toString().padStart(2, '0');
        const seconds = Math.floor((timeDiff % 60000) / 1000).toString().padStart(2, '0');
        timerText.textContent = `${hours}:${minutes}:${seconds}`;
        updateTimerDisplay(timeDiff, nextWindowChangeTime - currentWindowStartTime);
    }

    // Update eating window display and arc colors.
    function updateEatingWindowDisplay() {
        const start = parseTimeInput(eatingWindowStart.value);
        const end = parseTimeInput(eatingWindowEnd.value);
        const arcColor = isRunning ? "rgba(63, 136, 85, 0.2)" : "rgba(237, 109, 82, 0.2)";
        drawBackgroundArc(arcColor);
        if (!isRunning) {
            drawProgressArc(0, "rgba(237, 109, 82, 0.7)");
        } else if (timerStartTimestamp) {
            updateCountUpTimer();
        }
    }

    // Arc-drawing helper functions.
    function drawBackgroundArc(color = "rgba(63, 136, 85, 0.2)") {
        const radius = 40, gapSize = 45, center = { x: 50, y: 50 };
        const halfGap = gapSize / 2;
        const startAngle = (90 - halfGap) * Math.PI / 180;
        const endAngle = (90 + halfGap) * Math.PI / 180;
        const startX = center.x + radius * Math.cos(startAngle);
        const startY = center.y + radius * Math.sin(startAngle);
        const endX = center.x + radius * Math.cos(endAngle);
        const endY = center.y + radius * Math.sin(endAngle);
        const largeArcFlag = gapSize >= 180 ? 0 : 1;
        const path = `M ${startX},${startY} A ${radius},${radius} 0 ${largeArcFlag} 0 ${endX},${endY}`;
        backgroundArc.setAttribute('d', path);
        backgroundArc.setAttribute('stroke', color);
    }

    function drawProgressArc(progress, color = "rgba(63, 136, 85, 1)") {
        if (progress <= 0) {
            progressArc.setAttribute('d', '');
            return;
        }
        const radius = 40, gapSize = 45, center = { x: 50, y: 50 };
        const halfGap = gapSize / 2;
        const fullStartAngle = (90 + halfGap) * Math.PI / 180;
        const progressAngle = (360 - gapSize) * progress * Math.PI / 180;
        const endAngle = fullStartAngle + progressAngle;
        const startX = center.x + radius * Math.cos(fullStartAngle);
        const startY = center.y + radius * Math.sin(fullStartAngle);
        const endX = center.x + radius * Math.cos(endAngle);
        const endY = center.y + radius * Math.sin(endAngle);
        const largeArcFlag = progressAngle > Math.PI ? 1 : 0;
        const path = `M ${startX},${startY} A ${radius},${radius} 0 ${largeArcFlag} 1 ${endX},${endY}`;
        progressArc.setAttribute('d', path);
        progressArc.setAttribute('stroke', color);
    }

    function updateTimerDisplay(timeDiff, totalDuration) {
        const progress = Math.max(0, Math.min(1, 1 - (timeDiff / totalDuration)));
        const progressColor = isRunning ? "rgba(63, 136, 85, 1)" : "rgba(237, 109, 82, 0.7)";
        drawProgressArc(progress, progressColor);
        const progressEl = document.getElementById('progressValue');
        if (progressEl) {
            progressEl.style.color = isRunning ? "rgba(63, 136, 85, 0.7)" : "rgba(237, 109, 82, 0.7)";
        }
    }

    function initializeTimerUI() {
        const arcColor = isRunning ? "rgba(63, 136, 85, 0.2)" : "rgba(237, 109, 82, 0.2)";
        const progressColor = isRunning ? "rgba(63, 136, 85, 1)" : "rgba(237, 109, 82, 0.7)";
        drawBackgroundArc(arcColor);
        drawProgressArc(0, progressColor);
    }

    initializeTimerUI();

    // Parse time input string (e.g. "12:00") into a number.
    function parseTimeInput(timeString) {
        const [hours, minutes] = timeString.split(':').map(Number);
        return hours + minutes / 60;
    }

    // Save fasting record to the server.
    function saveFastingRecord(totalTime) {
        axios.post('/fasting/save', {
            total_fasting_minutes: (totalTime / (1000 * 60))
        })
        .then(response => {
            totalFastingTime = 0;
            saveStateToStorage();
        })
        .catch(error => {
            console.error("Error saving fasting record:", error);
        });
    }

    // Save current state to localStorage.
    function saveStateToStorage() {
        const state = {
            isRunning,
            totalFastingTime,
            nextWindowChangeTime: nextWindowChangeTime ? nextWindowChangeTime.getTime() : null,
            currentWindowStartTime: currentWindowStartTime ? currentWindowStartTime.getTime() : null,
            currentWindowEndTime: currentWindowEndTime ? currentWindowEndTime.getTime() : null,
            currentStartTime: currentStartTime ? currentStartTime.getTime() : null,
            eatingWindowStart: eatingWindowStart.value,
            eatingWindowEnd: eatingWindowEnd.value,
            // Save the running fasting duration (which may differ from the dropdown default)
            fastingDurationHours: runningFastingDurationHours,
            fastingDurationMinutes: runningFastingDurationMinutes,
            timerStartTimestamp,
            lastFastEndTime: lastFastEndTime ? lastFastEndTime.getTime() : null,
            windowDescriptionHtml: windowDescription.innerHTML
        };
        localStorage.setItem('fastingTimerState', JSON.stringify(state));
    }

    // Load state from localStorage.
    function loadStateFromStorage() {
        const stateJSON = localStorage.getItem('fastingTimerState');
        if (!stateJSON) return null;
        try {
            const parsed = JSON.parse(stateJSON);
            if (parsed.lastFastEndTime) {
                lastFastEndTime = new Date(parsed.lastFastEndTime);
            }
            return parsed;
        } catch (error) {
            console.error("Failed to parse stored state:", error);
            return null;
        }
    }

    // Initialize timer from stored state.
    function initializeTimerFromStorage() {
        const storedState = loadStateFromStorage();
        if (!storedState) {
            // No stored state, so default to not running (show start button)
            fastedTimeLabel.textContent = "Time since last fast";
            toggleTimer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 45 45" fill="none"><circle cx="22.5" cy="22.5" r="22.5" fill="#3F8855"/><path d="M32.5 20.4019C34.5 21.5566 34.5 24.4434 32.5 25.5981L20.5 32.5263C18.5 33.681 16 32.2376 16 29.9282V16.0718C16 13.7624 18.5 12.319 20.5 13.4737L32.5 20.4019Z" fill="white"/></svg>';
            const progressEl = document.getElementById('progressValue');
            if (progressEl) {
                progressEl.textContent = 'Progress 0%';
                progressEl.style.color = 'rgba(237, 109, 82, 0.7)';
            }
            return;
        }
        isRunning = storedState.isRunning;
        timerStartTimestamp = storedState.timerStartTimestamp;
        if (isRunning) {
            // Restore the running fasting duration and preserve the original start time.
            runningFastingDurationHours = storedState.fastingDurationHours || '16';
            runningFastingDurationMinutes = storedState.fastingDurationMinutes || '00';
            if (storedState.currentStartTime) {
                currentStartTime = new Date(storedState.currentStartTime);
            }
            startLabel.textContent = 'STARTED';
            startLabel.style.color = 'rgba(63, 136, 85, 0.7)';
            endLabel.style.color = 'rgba(63, 136, 85, 0.7)';
            if (storedState.currentWindowStartTime && storedState.currentWindowEndTime) {
                currentWindowStartTime = new Date(storedState.currentWindowStartTime);
                currentWindowEndTime = new Date(storedState.currentWindowEndTime);
            }
            if (storedState.windowDescriptionHtml) {
                windowDescription.innerHTML = storedState.windowDescriptionHtml;
            }
        } else {
            windowDescription.innerHTML = '<div><div style="padding: 11px 40px; border-radius: 25px; background-color:rgba(237, 109, 82, 0.7)"><div style="font-size: 20px;">Feeding</div></div></div>';
            fastedTimeLabel.textContent = "Time since last fast";
            startLabel.textContent = 'START';
            startLabel.style.color = 'rgba(237, 109, 82, 0.7)';
            endLabel.style.color = 'rgba(237, 109, 82, 0.7)';
            drawBackgroundArc("rgba(237, 109, 82, 0.2)");
        }
        totalFastingTime = storedState.totalFastingTime || 0;
        eatingWindowStart.value = storedState.eatingWindowStart || '12:00';
        eatingWindowEnd.value = storedState.eatingWindowEnd || '20:00';
        if (storedState.nextWindowChangeTime) {
            nextWindowChangeTime = new Date(storedState.nextWindowChangeTime);
        }
        if (storedState.lastFastEndTime) {
            lastFastEndTime = new Date(storedState.lastFastEndTime);
        }
        // For a running timer, do NOT update currentStartTime to now.
        if (!isRunning && storedState.currentStartTime) {
            currentStartTime = new Date(storedState.currentStartTime);
        } else if (isRunning && storedState.currentStartTime) {
            currentStartTime = new Date(storedState.currentStartTime);
        }
        updateStartTimeDisplay();
        updateEndTimeDisplay();
        updateEatingWindowDisplay();
        if (isRunning && timerStartTimestamp) {
            const progressEl = document.getElementById('progressValue');
            if (progressEl) {
                progressEl.style.color = "rgba(63, 136, 85, 0.7)";
            }
            updateCountUpTimer();
            intervalId = setInterval(updateCountUpTimer, 1000);
            toggleTimer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 45 45" fill="none"><circle cx="22.5" cy="22.5" r="22.5" fill="#ED6D52"/><rect x="13.5" y="13.5" width="18" height="18" rx="3" fill="white"/></svg>';
            eatingWindowStart.disabled = true;
            eatingWindowEnd.disabled = true;
            fastingDurationContainer.classList.add('hidden');
            endTimeDisplay.classList.remove('hidden');
        } else {
            toggleTimer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 45 45" fill="none"><circle cx="22.5" cy="22.5" r="22.5" fill="#3F8855"/><path d="M32.5 20.4019C34.5 21.5566 34.5 24.4434 32.5 25.5981L20.5 32.5263C18.5 33.681 16 32.2376 16 29.9282V16.0718C16 13.7624 18.5 12.319 20.5 13.4737L32.5 20.4019Z" fill="white"/></svg>';
            fastingDurationContainer.classList.remove('hidden');
            endTimeDisplay.classList.add('hidden');
            timerText.textContent = '00:00:00';
            const progressEl = document.getElementById('progressValue');
            if (progressEl) {
                progressEl.textContent = 'Progress 0%';
                progressEl.style.color = "rgba(237, 109, 82, 0.7)";
            }
        }
        saveStateToStorage();
    }

    // Check if currently in Eating Window based on current time.
    function isCurrentlyEatingWindow() {
        const now = new Date();
        const start = new Date(`${now.toDateString()} ${eatingWindowStart.value}`);
        const end = new Date(`${now.toDateString()} ${eatingWindowEnd.value}`);
        if (end <= start) end.setDate(end.getDate() + 1);
        return now >= start && now < end;
    }

    setInterval(() => {
        if (!isRunning && lastFastEndTime) {
            updateTimeSinceLastFast();
        }
    }, 1000);

    updateStartTimeDisplay();
    updateEndTimeDisplay();
    updateEatingWindowDisplay();

    // Save Note functionality
    const saveNoteBtn = document.getElementById('save-note');
    const noteInput = document.getElementById('note-input');

    if (saveNoteBtn && noteInput) {
        saveNoteBtn.addEventListener('click', function() {
            const note = noteInput.value.trim();
            if (note) {
                // Get today's date in YYYY-MM-DD format
                let recordDate = new Date().toISOString().split('T')[0]; // Default to today

                // If on insights page, get date from URL
                if (window.location.pathname.includes('insights')) {
                    const urlParams = new URLSearchParams(window.location.search);
                    const urlDate = urlParams.get('date');
                    if (urlDate) {
                        recordDate = urlDate;
                    }
                }

                // Save the note
                fetch('/notes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        recordDate: recordDate,
                        note: note
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Clear input
                    noteInput.value = '';
                    
                    // Show success message
                    alert('Note saved successfully!');
                })
                .catch(error => {
                    console.error('Error saving note:', error);
                    alert('Failed to save note. Please try again.');
                });
            }
        });
    }
});
