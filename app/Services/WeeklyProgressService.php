<?php

namespace App\Services;

use App\Models\FastingRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WeeklyProgressService
{
    /**
     * Returns an array of records for the specified week.
     * Each element looks like:
     * [
     *   'date'                  => 'YYYY-MM-DD',
     *   'total_fasting_minutes' => 123
     * ]
     *
     * @param  string|null $dateString  The date (YYYY-MM-DD) the user clicked.
     *                                  If null, defaults to current date.
     * @return array
     */
    public function getWeeklyDataArray(?string $dateString = null): array
    {
        $userId = Auth::id();
        if (!$userId) {
            // If needed, you can handle this scenario differently, 
            // e.g., throw an exception or return an empty array
            return [];
        }

        // 1) Parse the user-selected date or default to now()
        $selectedDate = $dateString
            ? Carbon::parse($dateString)
            : Carbon::now();

        // 2) Calculate start (Monday) and end (Sunday) of the week
        $startOfWeek = $selectedDate->copy()->startOfWeek();
        $endOfWeek   = $selectedDate->copy()->endOfWeek();

        // 3) Fetch the records for the user in that date range
        $records = FastingRecord::where('user_id', $userId)
            ->whereBetween('record_date', [
                $startOfWeek->format('Y-m-d'),
                $endOfWeek->format('Y-m-d'),
            ])
            ->orderBy('record_date', 'asc')
            ->get();

        // 4) Convert results into a map: [ 'YYYY-MM-DD' => total_minutes ]
        $recordMap = $records->mapWithKeys(function ($r) {
            return [$r->record_date => $r->total_fasting_minutes];
        })->toArray();

        // 5) Build final array for each day from Monday to Sunday
        $chartData = [];
        $currentDate = $startOfWeek->copy();

        while ($currentDate->lte($endOfWeek)) {
            $dateString = $currentDate->format('Y-m-d');
            $chartData[] = [
                'date'                  => $dateString,
                'total_fasting_minutes' => $recordMap[$dateString] ?? 0,
            ];
            $currentDate->addDay();
        }

        return $chartData;
    }

    // Get all records for the user
    public function getFastingRecords()
    {
        $userId = Auth::id();
        if (!$userId) {
            return "User not found";
        }
        $records = FastingRecord::where('user_id', $userId)
            ->where('total_fasting_minutes', ">=",960) // 16 hours
            ->pluck('record_date'); // Fetch only record dates

        return $records;
    }


 /**
     * Creates note for user and adds it to the database
     * 
     * @param string|null $recordDate, $note
     * @return array
     */
    public function writeNotes($recordDate, $note) {
        $userId = Auth::id();
        if (!$userId) {
            return "Error: User not found";
        }

        \App\Models\Note::create([
            'user_id'=> $userId,
            'notes'=> $note,
            'date'=> $recordDate
        ]);

        return 'Notes added successfully'; // Returns success message
    }
 /**
     * Calculate user's fasting statistics
     * 
     * @param string|null $targetDate
     * @return array
     */
    public function calculateFastingStats(?string $targetDate = null)
    {
        $userId = Auth::id();
        if (!$userId) {
            return [
                'fastingDays' => 0,
                'streakDays' => 0,
                'averageTime' => '0h 0m',
                'successRate' => 0
            ];
        }

        if ($targetDate) {
            $currentMonth = Carbon::parse($targetDate);
        } else {
            $currentMonth = Carbon::now();
        }

        // Current month's records
        $monthStart = $currentMonth->copy()->startOfMonth();
        $monthEnd = $currentMonth->copy()->endOfMonth();

        // All fasting records for the current month
        $allMonthlyRecords = FastingRecord::where('user_id', $userId)
            ->whereBetween('record_date', [
                $monthStart->format('Y-m-d'), 
                $monthEnd->format('Y-m-d')
            ])
            ->orderBy('record_date')
            ->get();

        // Records with 16+ hours fasting
        $longFastRecords = $allMonthlyRecords->where('total_fasting_minutes', '>=', 960);

        // Calculate fasting days (only 16+ hour fasts)
        $fastingDays = $longFastRecords->count();

        // Calculate current streak (based on consecutive 16+ hour fasts)
        $streak = $this->getCurrentStreak($longFastRecords);

        // Calculate average fasting time (including all fasts)
        $averageFastingMinutes = $allMonthlyRecords->avg('total_fasting_minutes');
        $averageHours = floor($averageFastingMinutes / 60);
        $averageMinutes = round($averageFastingMinutes % 60);
        $averageTime = "{$averageHours}h {$averageMinutes}m";

        // Calculate success rate (16+ hours fasts out of total month days)
        $totalPossibleDays = $currentMonth->daysInMonth;
        $successRate = $totalPossibleDays > 0 
            ? round(($longFastRecords->count() / $totalPossibleDays) * 100) 
            : 0;

        return [
            'fastingDays' => $fastingDays,
            'streakDays' => $streak,
            'averageTime' => $averageTime,
            'successRate' => $successRate
        ];
    }

  /**
 * Calculate current consecutive fasting streak
 * 
 * @param \Illuminate\Support\Collection $records
 * @return int
 */
private function getCurrentStreak($records)
{
    if ($records->isEmpty()) {
        return 0;
    }

    // Get today's date
    $today = Carbon::now()->format('Y-m-d');

    // If no record for today, streak is 0
    $todayRecord = $records->firstWhere('record_date', $today);
    if (!$todayRecord) {
        return 0;
    }

    // Start streak calculation from today
    $currentStreak = 1;  // Start with 1 for today
    
    // Convert collection to array of dates, sorted in descending order
    $datesArray = $records->pluck('record_date')
        ->sort(function($a, $b) {
            return strtotime($b) - strtotime($a);  // Sort descending
        })
        ->values()
        ->toArray();
    
    // Find today's position in the array
    $todayPosition = array_search($today, $datesArray);
    if ($todayPosition === false) {
        return 0;
    }
    
    $previousDate = Carbon::parse($today);
    
    // Start from the next record after today
    for ($i = $todayPosition + 1; $i < count($datesArray); $i++) {
        $currentDate = Carbon::parse($datesArray[$i]);
        
        // Check if dates are consecutive
        if ($previousDate->copy()->subDay()->format('Y-m-d') === $currentDate->format('Y-m-d')) {
            $currentStreak++;
            $previousDate = $currentDate;
        } else {
            // Streak broken
            break;
        }
    }
    
    return $currentStreak;
}

}