<?php

namespace App\Http\Controllers\Insights;
use App\Http\Controllers\Controller;
use App\Models\FastingRecord;

use Illuminate\Support\Facades\Auth;

class FastingRecordController extends Controller
{
    public function getFastingRecords()
    {
        $userId = Auth::id();
        if (!$userId) {
            return view('insights', ['fastingRecords' => []]);
        }
    
        $fastingRecords = FastingRecord::where('user_id', $userId)
            ->where('total_fasting_minutes', 960) // Filter only 16-hour fasts
            ->select('record_date')
            ->get()
            ->pluck('record_date') // Extract only the dates
            ->toArray();
    
        return view('insights.insights', compact('fastingRecords'));
    } 
}

