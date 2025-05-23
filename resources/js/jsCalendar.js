var jsCalendar = (function(){

    
    // Constructor
    function JsCalendar(target, completedDates = []) {
        let urlParams = new URLSearchParams(window.location.search);
        let dateParam = urlParams.get('date');
        
        let initialDate;
        if (dateParam) {
            // Parse date components explicitly to avoid timezone issues
            const [year, month, day] = dateParam.split('-').map(Number);
            initialDate = new Date(year, month - 1, day); // month is 0-indexed in JS
        } else {
            initialDate = new Date();
        }
        
        this._setTarget(target);
        this._init();
        this._setDate(initialDate);

        // Convert completedDates to local timezone and ensure proper alignment
        this.completedDates = completedDates.map(date => {
            const utcDate = new Date(date); // Parse the date as UTC
            const localDate = new Date(utcDate.getTime() + utcDate.getTimezoneOffset() * 60000); // Adjust to local timezone
            return localDate.toDateString(); // Convert to string for easy comparison
        });

        this._create();
        this._update();
        this._isFrozen = false;
    }
    

    // Initialize calendar
    JsCalendar.prototype._init = function() {
        this._elements = {};
        this._now = null;
        this._date = null;
        this._selected = [];

        // Default English language with fixed formats
        this.language = {
            months: ['January', 'February', 'March', 'April', 'May', 'June', 
                     'July', 'August', 'September', 'October', 'November', 'December'],
            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            // Fixed 'Month Year' format
            dateStringParser: function(key, date) {
                if (key === 'MONTH') {
                    return this.months[date.getMonth()] + ' ' + date.getFullYear();
                } else if (key === 'DDD') {
                    return this.days[date.getDay()]
                }
                return key;
            },

        };
    };

    // Set target element
    JsCalendar.prototype._setTarget = function(element) {
        if (typeof element === 'string') {
            // Use querySelector to allow for ID or class selectors
            this._target = document.querySelector(element);
        } else if (element instanceof HTMLElement) {
            this._target = element;
        } else {
            throw new Error('jsCalendar: Invalid target element.');
        }

        if (!this._target) {
            throw new Error('jsCalendar: Target element not found.');
        }

        this._target.classList.add('jsCalendar');
    };

    // Set current date
    JsCalendar.prototype._setDate = function(date) {
        this._now = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        this._date = new Date(this._now.getFullYear(), this._now.getMonth(), 1);
    };

    // Create calendar structure
    JsCalendar.prototype._create = function() {
        var that = this;

        // Create table
        this._elements.table = document.createElement('table');
        this._elements.table.className = 'jsCalendar-table';

        // Create table header
        this._elements.head = document.createElement('thead');
        this._elements.table.appendChild(this._elements.head);

        // Create table body
        this._elements.body = document.createElement('tbody');
        this._elements.table.appendChild(this._elements.body);

        // Insert table into target
        this._target.appendChild(this._elements.table);

        // Create navigation and month title row
        var navRow = document.createElement('tr');
        var navCell = document.createElement('th');
        navCell.colSpan = 7;
        navCell.className = 'jsCalendar-nav-row';
        navRow.appendChild(navCell);
        this._elements.head.appendChild(navRow);


        this._elements.month = document.createElement('span');
        this._elements.month.className = 'jsCalendar-title';
        navCell.appendChild(this._elements.month);



        this._elements.navRight = document.createElement('button');

        this._elements.navRight.className = 'jsCalendar-nav-right';
        navCell.appendChild(this._elements.navRight);

        // Create navigation buttons and month title
        this._elements.navLeft = document.createElement('button');

        this._elements.navLeft.className = 'jsCalendar-nav-left';
        navCell.appendChild(this._elements.navLeft);


        // Attach event listeners for navigation
        this._elements.navLeft.addEventListener('click', function(){
            that.previous();
        });
        this._elements.navRight.addEventListener('click', function(){
            that.next();
        });

        // Create days of the week row
        var daysRow = document.createElement('tr');
        daysRow.className = 'jsCalendar-week-days';
        this._elements.head.appendChild(daysRow);

        this._elements.days = [];
        for (var i = 0; i < 7; i++) {
            var dayCell = document.createElement('th');
            // Using a fixed date to get day abbreviation
            dayCell.textContent = this.language.dateStringParser('DDD', new Date(2023, 0, i + 1));
            dayCell.className = 'jsCalendar-day-name';
            daysRow.appendChild(dayCell);
            this._elements.days.push(dayCell);
        }

        // Create body rows (6 weeks to cover all possible month layouts)
        this._elements.bodyRows = [];
        this._elements.bodyCols = [];
        for (var row = 0; row < 6; row++) { // 6 weeks
            var tr = document.createElement('tr');
            this._elements.body.appendChild(tr);
            this._elements.bodyRows.push(tr);
            for (var col = 0; col < 7; col++) {
                var td = document.createElement('td');
                td.className = 'jsCalendar-day';
                tr.appendChild(td);
                this._elements.bodyCols.push(td);
            }
        }
    };

    JsCalendar.prototype._update = function() {
        if (this._isFrozen) return;
    
        var month = this._getVisibleMonth(this._date);
        this._active = month.days.slice();
        this._elements.month.textContent = month.name;
    
        for (var i = 0; i < month.days.length; i++) {
            var day = month.days[i];
            var cell = this._elements.bodyCols[i];
            cell.textContent = day.getDate();
            cell.className = 'jsCalendar-day';
    
            // Ensure proper comparison with local timezone
            const localDay = new Date(day.getTime() + day.getTimezoneOffset() * 60000);
    
            if (localDay.toDateString() === this._now.toDateString()) {
                cell.classList.add('jsCalendar-current');
                cell.classList.add('jsCalendar-selected'); // Add the selection ring
            }
    
            if (localDay.getMonth() !== this._date.getMonth()) {
                cell.classList.add('jsCalendar-other-month');
            }
            if (this.completedDates.includes(localDay.toDateString())) {
                cell.classList.add('completed');
            }
    
            (function(cell, day, calendarInstance) {
                cell.addEventListener('click', () => {
                    calendarInstance._selectDate(day, cell);
                });
            })(cell, day, this);
        }
    };
    
    

    // Get visible month data
    JsCalendar.prototype._getVisibleMonth = function(date) {
        var year = date.getFullYear();
        var month = date.getMonth();

        // First day of the month
        var firstDay = new Date(year, month, 1);
        var startDay = firstDay.getDay(); // 0 (Sun) to 6 (Sat)

        // Number of days in the current month
        var daysInMonth = new Date(year, month + 1, 0).getDate();

        // Number of days in the previous month
        var daysInPrevMonth = new Date(year, month, 0).getDate();

        // Total cells (6 weeks * 7 days = 42)
        var totalCells = 42;

        var days = [];

        // Fill previous month's trailing days
        for (var i = startDay; i > 0; i--) { // startDay determines how many days to show from previous month
            days.push(new Date(year, month - 1, daysInPrevMonth - i + 1));
        }

        // Fill current month's days
        for (var d = 1; d <= daysInMonth; d++) {
            days.push(new Date(year, month, d));
        }

        // Fill next month's leading days
        while (days.length < totalCells) {
            var lastDay = days[days.length - 1];
            days.push(new Date(lastDay.getFullYear(), lastDay.getMonth(), lastDay.getDate() + 1));
        }

        // Get month name with year, e.g., "January 2024"
        var name = this.language.dateStringParser('MONTH', firstDay);

        return {
            name: name,
            days: days
        };
    };

    // Navigate to next month
    JsCalendar.prototype.next = function(){
        let nextMonth = new Date(this._date.getFullYear(), this._date.getMonth() + 1, 1);
        let y = nextMonth.getFullYear();
        let m = String(nextMonth.getMonth() + 1).padStart(2, '0');
        let d = '01';
        window.location.href = `/insights?date=${y}-${m}-${d}`;
    };

    // Navigate to previous month
    JsCalendar.prototype.previous = function(){
        let prevMonth = new Date(this._date.getFullYear(), this._date.getMonth() - 1, 1);
        let y = prevMonth.getFullYear();
        let m = String(prevMonth.getMonth() + 1).padStart(2, '0');
        let d = '01';
        window.location.href = `/insights?date=${y}-${m}-${d}`;
    };

    JsCalendar.prototype._selectDate = function(day, cell) {
        let y = day.getFullYear();
        let m = String(day.getMonth() + 1).padStart(2, '0');
        let d = String(day.getDate()).padStart(2, '0');
        let selectedDate = `${y}-${m}-${d}`;
        
        // Dispatch the dateSelected event before redirecting
        const event = new CustomEvent('dateSelected', {
            detail: { 
                date: selectedDate 
            }
        });
        document.dispatchEvent(event);
        
        // Then redirect to the insights page with the selected date
        window.location.href = `/insights?date=${selectedDate}`;
    };

    // Auto init calendars on page load
    window.addEventListener('load', function() {
        var calendars = document.querySelectorAll('.auto-jsCalendar');
        const rawData = calendars[0].dataset.calendarData;
        const decodedData = rawData.replace(/\\u0022/g, '"');
        const datesArray = JSON.parse(decodedData);
    
        calendars.forEach(function(cal) {
            new JsCalendar(cal, datesArray); // ✅ Pass completed dates
        });
    }, false);
    

    return JsCalendar;
})();
