<?php

namespace App\Http\Controllers\Insights;

use App\Http\Controllers\Controller;
use App\Services\WeeklyProgressService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WeeklyProgressChartController extends Controller
{
    protected $service;

    public function __construct(WeeklyProgressService $service)
    {
        $this->service = $service;
    }

    public function showProgress(Request $request)
    {
        $selectedDate = $request->input('date');
        if ($selectedDate) {
            $monthStart = Carbon::parse($selectedDate)->startOfMonth();
            Carbon::setTestNow($monthStart);
        }

        // 1) Get array from the service
        $weeklyRecords = $this->service->getWeeklyDataArray($request->input('date'));
        $fastingFullRecords = $this->service->getFastingRecords();
        
        // 2) Get fasting stats
        $fastingStats = $this->service->calculateFastingStats($request->input('date'));

        Carbon::setTestNow(); // revert the time shift

        // 3) Return the insights Blade with the array and stats
        return view('insights.insights', compact(
            'weeklyRecords', 
            'fastingFullRecords', 
            'fastingStats'
        ));
    }
}