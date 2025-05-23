<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeeklyProgressService;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    protected $weeklyProgressService;
    
    public function __construct(WeeklyProgressService $weeklyProgressService)
    {
        $this->weeklyProgressService = $weeklyProgressService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recordDate' => 'required|date',
            'note' => 'required|string'
        ]);
        
        $result = $this->weeklyProgressService->writeNotes(
            $validated['recordDate'],
            $validated['note']
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Note saved successfully',
            'note' => $validated['note'],
            'date' => $validated['recordDate']
        ]);
    }
    
    public function index(Request $request)
    {
        $date = $request->query('date', null);
        
        $query = \App\Models\Note::where('user_id', Auth::id());
        
        // Only filter by date if a date is provided
        if ($date) {
            // Ensure exact date matching with converted date
            $query->whereDate('date', $date);
        }
        
        $notes = $query->orderBy('date', 'desc')->get();
        
        return response()->json([
            'notes' => $notes
        ]);
    }
}
