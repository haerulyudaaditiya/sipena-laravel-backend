<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileApiController extends Controller
{
    /**
     * Memperbarui FCM token untuk pengguna yang sedang login.
     * Ini digunakan untuk push notification.
     */
    public function updateFcmToken(Request $request)
    {
        $request->validate(['fcm_token' => 'required|string']);

        try {
            // Update token untuk user yang sedang login
            $request->user()->update(['fcm_token' => $request->fcm_token]);
            return response()->json(['message' => 'FCM token berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memperbarui FCM token.'], 500);
        }
    }

    /**
     * Mengambil ringkasan data cuti untuk pengguna yang login.
     * Ini digunakan untuk ditampilkan di dasbor.
     */
    public function getLeaveSummary()
    {
        $user = Auth::user();
        $employee = $user->employee; // Mengambil data employee melalui relasi
        $settings = Setting::first(); // Mengambil pengaturan kuota cuti

        if (!$employee || !$settings) {
            // Memberikan nilai default jika pengaturan atau data karyawan tidak ada
            return response()->json([
                'annual_leave_quota' => 0,
                'taken_leave' => 0,
                'remaining_leave' => 0,
            ]);
        }

        $kuota = $settings->annual_leave_quota;
        // Memanggil helper method yang sudah kita buat di model Employee
        $diambil = $employee->getUsedAndPendingLeaveDays();
        $sisa = $kuota - $diambil;

        return response()->json([
            'annual_leave_quota' => $kuota,
            'taken_leave' => $diambil,
            'remaining_leave' => $sisa,
        ]);
    }
}
