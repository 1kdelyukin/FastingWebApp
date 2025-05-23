<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FastingRecord;
use Illuminate\Support\Facades\Auth;

class FastingController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'total_fasting_minutes' => 'required|numeric',
        ]);
    
        $recordDate = now()->format('Y-m-d');
        $userId = $request->user()->id; // or Auth::id()
    
        // Retrieve the existing record or create a new one with total_fasting_minutes = 0
        $record = FastingRecord::firstOrNew(
            [
                'user_id'     => $userId,
                'record_date' => $recordDate,
            ],
            [
                'total_fasting_minutes' => 0,
            ]
        );
    
        // Add the incoming fasting minutes to the existing total
        $record->total_fasting_minutes += $request->input('total_fasting_minutes');
        $record->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Fasting record saved successfully!',
            'record'  => $record
        ]);
    }

    public function getLast(Request $request)
{
    $userId = $request->user()->id;
    $lastRecord = FastingRecord::where('user_id', $userId)
                    ->orderBy('updated_at', 'desc')
                    ->first();

    if ($lastRecord) {
        return response()->json([
            'success' => true,
            'last_fasting_end' => $lastRecord->updated_at,
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'No fasting records found.'
        ], 404);
    }
}

    
}
