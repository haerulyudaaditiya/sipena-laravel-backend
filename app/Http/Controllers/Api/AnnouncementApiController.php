<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AnnouncementApiController extends Controller
{
    /**
     * Mengambil satu pengumuman terbaru yang sudah dipublikasikan.
     */
    public function getLatest()
    {
        $latestAnnouncement = Announcement::where('status', 'published')
            ->latest('published_at') // Ambil yang tanggal publikasinya paling baru
            ->first();

        if ($latestAnnouncement) {
            return response()->json([
                'status' => 'success',
                'data' => $latestAnnouncement
            ]);
        }

        // Kirim response kosong jika tidak ada pengumuman
        return response()->json([
            'status' => 'success',
            'data' => null
        ]);
    }
}
