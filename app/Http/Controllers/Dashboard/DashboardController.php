<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $service;
    public function showDashboard(Request $request)
    {
        // get Todays date
        $today = Carbon::now()->format('F j Y');

 
        // return date
        return view('dashboard.dashboard', compact('today'));
    }
}
