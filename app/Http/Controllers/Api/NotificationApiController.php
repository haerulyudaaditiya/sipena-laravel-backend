<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationApiController extends Controller
{
    /**
     * Mengambil semua notifikasi untuk pengguna yang login.
     */
    public function index()
    {
        $employeeId = Auth::user()->employee_id;
        $notifications = Notification::where('employee_id', $employeeId)
            ->latest() // Tampilkan yang terbaru di atas
            ->get();
            
        return response()->json(['data' => $notifications]);
    }

    /**
     * Menandai notifikasi sebagai 'read'.
     */
    public function markAsRead(Notification $notification)
    {
        // Keamanan: Pastikan notifikasi ini milik pengguna yang login
        if ($notification->employee_id !== Auth::user()->employee_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update(['status' => 'read']);

        return response()->json(['message' => 'Notification marked as read.']);
    }

    /**
     * Mengambil jumlah notifikasi yang belum dibaca.
     */
    public function unreadCount()
    {
        $employeeId = Auth::user()->employee_id;
        $count = Notification::where('employee_id', $employeeId)
            ->where('status', 'unread')
            ->count();
            
        return response()->json(['unread_count' => $count]);
    }
}
