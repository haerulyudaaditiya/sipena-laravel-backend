<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Ambil semua log, urutkan dari yang terbaru, dan gunakan pagination
        $activityLogs = ActivityLog::with('user')->latest('activity_date')->paginate(30);
        
        return view('activity-logs.index', compact('activityLogs'));
    }
}
