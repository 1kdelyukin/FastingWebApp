<?php

namespace App\Http\Controllers\Insights;

use App\Http\Controllers\Controller;
use App\Services\WeeklyProgressService;
use Illuminate\Http\Request;

class WeeklyProgressController extends Controller
{
    protected $service;

    // Constructor injection
    public function __construct(WeeklyProgressService $service)
    {
        $this->service = $service;
    }

    public function getWeeklyData(Request $request)
    {
        // 1) Get the array from the service
        $chartData = $this->service->getWeeklyDataArray($request->input('date'));

        // 2) Return JSON
        return response()->json($chartData);
    }
}