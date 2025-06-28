<?php

namespace App\Http\Controllers\Web;

use App\Models\LeaveRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User; // DITAMBAHKAN: Import model User
use Illuminate\Support\Facades\Http; // DITAMBAHKAN: Import HTTP Client Laravel

class LeaveRequestController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen cuti dengan semua pengajuan.
     */
    public function index()
    {
        // Ambil semua data cuti, eager load relasi 'employee' untuk efisiensi
        // Urutkan berdasarkan status 'pending' terlebih dahulu, lalu yang terbaru
        $leaveRequests = LeaveRequest::with('employee')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->latest('created_at')
            ->paginate(20); // Menggunakan paginate untuk performa lebih baik

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|nullable',
        ]);

        try {
            $leaveRequest->update([
                'status' => $request->status,
                'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
            ]);

            // --- Logika Pembuatan Notifikasi (Database) ---
            $statusText = $request->status === 'approved' ? 'disetujui' : 'ditolak';
            $title = "Pengajuan Cuti Anda Telah $statusText";
            $message = "Pengajuan cuti Anda untuk tanggal " .
                date('d M Y', strtotime($leaveRequest->start_date)) .
                " telah $statusText oleh admin.";

            if ($request->status === 'rejected') {
                $message .= " Alasan: " . $request->rejection_reason;
            }

            Notification::create([
                'employee_id' => $leaveRequest->employee_id,
                'title'       => $title,
                'message'     => $message,
                'status'      => 'unread',
                'related_model'  => LeaveRequest::class,
                'related_id'     => $leaveRequest->id,
            ]);

            // --- DITAMBAHKAN KEMBALI: Logika Pengiriman Push Notification Manual ---
            $employeeUser = User::where('employee_id', $leaveRequest->employee_id)->first();

            if ($employeeUser && $employeeUser->fcm_token) {
                $serverKey = env('FCM_SERVER_KEY');

                // DIUBAH: Hasil request ditampung dalam variabel $response
                $response = Http::withHeaders([
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type' => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $employeeUser->fcm_token,
                    'notification' => [
                        'title' => $title,
                        'body' => $message,
                        'sound' => 'default',
                    ],
                    'data' => [
                        'related_model' => 'LeaveRequest',
                        'related_id' => $leaveRequest->id
                    ],
                ]);

                // DITAMBAHKAN: Mencatat respons dari FCM ke dalam file log
                Log::info('FCM Response: ' . $response->body());
            }

            return redirect()->route('leave_requests.index')->with('success', "Pengajuan cuti berhasil divalidasi.");
        } catch (\Exception $e) {
            // Catat juga error jika ada pengecualian
            Log::error('FCM Send Exception: ' . $e->getMessage());
            return redirect()->route('leave_requests.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data pengajuan cuti.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        try {
            $leaveRequest->delete();
            return redirect()
                ->route('leave-requests.index')
                ->with('success', 'Data pengajuan cuti berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('leave-requests.index')
                ->with('error', 'Gagal menghapus data pengajuan cuti.');
        }
    }
}
