<div class="intermittent-fasting-timer flex flex-col items-center max-w-md mx-auto rounded-xl">
  
    <p class="text-center mt-4 font-bold text-black text-xs" id="windowDescription">

    </p>
    <div class="p-4">
        <style>
            /* Hide dropdown arrows in all browsers */
            select {
                -webkit-appearance: none !important;
                -moz-appearance: none !important;
                appearance: none !important;
                background-image: none !important;
            }
            
            /* Specifically for Firefox */
            select::-ms-expand {
                display: none;
            }
            
            /* For IE/Edge */
            select::-ms-expand {
                display: none;
            }
        </style>

        <div class="relative flex items-center mx-auto mb-3">
            <svg id="svgContainer" viewBox="0 0 100 100" width="300" height="300">
                <!-- Background semi-circle -->
                <path id="backgroundArc"
                      fill="none"
                      stroke="rgba(63, 136, 85, 0.2)"
                      stroke-width="8"
                      stroke-linecap="round" />
                
                <!-- Progress semi-circle -->
                <path id="progressArc"
                      fill="none"
                      stroke="rgba(63, 136, 85, 1)"
                      stroke-width="8"
                      stroke-linecap="round" />
            </svg>

            <!-- Timer Text -->
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span id="fastedTime" class="text-base font-bold">You’ve fasted for</span>
                <span class="text-5xl font-bold" id="timerText">00:00:00</span>
                <span id="progressValue" class="text-base font-bold" style="color:rgba(63, 136, 85, 0.7);">Progress 0%</span>
            </div>
        </div>

        <!-- Eating Window Time Inputs -->
        <div class="flex justify-between mb-4 ">
            <div class="flex flex-col items-center">
                <label class="block text-xs font-bold mb-1" for="eatingWindowStart" id="startLabel" style="color: rgba(237, 109, 82, 0.7)">
                    START
                </label>
                <div id="startTimeDisplay" class="shadow appearance-none border rounded text-xs w-28 text-center h-7 flex items-center justify-center">
                    <!-- Date will be populated by JS -->
                </div>
                <input type="hidden" id="eatingWindowStart" value="{{ $startTime }}">
            </div>
            <!-- Start/Stop Button -->
            <div id="toggleTimer">
                <svg id="playIcon" xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 45 45"
                    fill="none">
                    <circle cx="22.5" cy="22.5" r="22.5" fill="#3F8855" />
                    <path
                        d="M32.5 20.4019C34.5 21.5566 34.5 24.4434 32.5 25.5981L20.5 32.5263C18.5 33.681 16 32.2376 16 29.9282V16.0718C16 13.7624 18.5 12.319 20.5 13.4737L32.5 20.4019Z"
                        fill="white" />
                </svg>
                <svg id="stopIcon" xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 45 45"
                    fill="none">
                    <circle cx="22.5" cy="22.5" r="22.5" fill="#ED6D52" />
                    <rect x="13.5" y="13.5" width="18" height="18" rx="3" fill="white" />
                </svg>
            </div>

            <div class="flex flex-col items-center">
                <label class="block text-xs font-bold mb-1" for="eatingWindowEnd" id="endLabel" style="color: rgba(237, 109, 82, 0.7)">
                    GOAL
                </label>
                <div id="fastingDurationContainer" class="shadow appearance-none border rounded text-xs w-28 text-center h-7 flex items-center justify-center">
                    <div class="flex items-center justify-between w-full px-3">
                        <div class="w-10 flex justify-center relative">
                            <div class="flex items-center">
                                <select id="fastingDurationHours" class="bg-transparent border-none outline-none text-xs text-center w-6 p-0"
                                    style="-webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; text-align-last: center;">
                                    <!-- Hours options will be populated by JS -->
                                </select>
                                <div class="pointer-events-none flex items-center ml-0.5 pr-2">
                                    <svg class="fill-current h-2 w-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex-none">
                            <span>:</span>
                        </div>
                        <div class="w-10 flex justify-center relative">
                            <div class="flex items-center">
                                <select id="fastingDurationMinutes" class="bg-transparent border-none outline-none text-xs text-center w-6 p-0"
                                    style="-webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; text-align-last: center;">
                                    <!-- Minutes options will be populated by JS -->
                                </select>
                                <div class="pointer-events-none flex items-center ml-0.5">
                                    <svg class="fill-current h-2 w-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="endTimeDisplay" class="shadow appearance-none border rounded text-gray-700 text-xs hidden w-28 text-center h-7 flex items-center justify-center">
                    <!-- End date will be populated by JS -->
                </div>
                <input type="hidden" id="eatingWindowEnd" value="{{ $endTime }}">
            </div>

        </div>


                <!-- Notes Input Area -->
        <div class="bg-white rounded-lg shadow-md p-4 list-none mx-auto max-w-lg w-full">
            <textarea 
                id="note-input" 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-customGreen focus:border-transparent" 
                rows="3"
                placeholder="Add your fasting notes for today..."></textarea>
            
            <div class="mt-2">
                <button 
                    id="save-note" 
                    class=" w-full bg-customGreen text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition duration-200">
                    Save Memo
                </button>
            </div>
        </div>

            

    </div>
</div>