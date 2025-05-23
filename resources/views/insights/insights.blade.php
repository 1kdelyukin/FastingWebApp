@extends('layouts.app')

@push('meta')
    @vite([
        'resources/css/jsCalendar.css', 
        'resources/js/barGraph.js',
        'resources/js/jsCalendar.js',
        'resources/js/statsBox.js',
        'resources/js/notes.js',
        'resources/js/notesHistory.js'    ])
@endpush

@section('content')
<div class="space-y-4 p-4">
    <!-- Hidden stats data for JavaScript to consume -->
    <div id="fasting-stats" data-stats="{{ json_encode($fastingStats) }}" class="hidden"></div>

    <!--Calendar -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="auto-jsCalendar" data-calendar-data=@json($fastingFullRecords)></div>
    </div>

    <!-- Statistics Box -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <div id="stats-box" class="grid grid-cols-2 gap-3 text-center"></div>
    </div>

<!-- Bar Graph -->
<div class="bg-white rounded-lg shadow-lg p-4">
    <x-weekly-bar-graph class="w-full h-60" :data="$weeklyRecords" />
</div>
       <!-- Add notes section below the bar graph -->
       <div class="bg-white rounded-lg shadow-md p-4 mt-4">
        <h2 class="text-lg font-semibold mb-3">Your Notes History</h2>
        <div id="notes-history" class="space-y-3">
            <!-- Notes will be loaded here via JavaScript -->
            <p class="text-gray-500 text-center">Loading notes...</p>
        </div>
    </div>
</div>
@endsection