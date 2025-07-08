<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveRequestApiController extends Controller
{
    /**
     * Menyimpan pengajuan cuti baru dari karyawan.
     * @method POST
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee_id; // Mengambil employee_id dari user yang login
        if (!$employeeId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data karyawan tidak terhubung dengan akun user ini.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => ['required', Rule::in(['annual', 'sick', 'personal', 'other'])],
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'contact' => 'nullable|string|max:20',
        ], [
            'start_date.after_or_equal' => 'Tanggal mulai cuti tidak boleh tanggal yang sudah lewat.',
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // --- AWAL LOGIKA VALIDASI KUOTA CUTI ---
            if ($request->type === 'annual') {
                $employee = $user->employee; // Mengambil model Employee dari relasi User
                $settings = Setting::first();

                if ($employee && $settings) {
                    $kuotaCutiTahunan = $settings->annual_leave_quota;
                    // Memanggil helper method yang ada di model Employee
                    $cutiTerpakaiDanPending = $employee->getUsedAndPendingLeaveDays();

                    $durasiPengajuanBaru = \Carbon\Carbon::parse($request->start_date)
                        ->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1;

                    $sisaCuti = $kuotaCutiTahunan - $cutiTerpakaiDanPending;

                    if ($durasiPengajuanBaru > $sisaCuti) {
                        return response()->json([
                            'status' => 'error',
                            'message' => "Pengajuan gagal. Sisa kuota cuti tahunan Anda hanya {$sisaCuti} hari."
                        ], 400); // 400 Bad Request
                    }
                }
            }
            // --- AKHIR LOGIKA VALIDASI KUOTA CUTI ---

            $leaveRequest = LeaveRequest::create([
                'employee_id' => $employeeId,
                'type' => $request->type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => 'pending',
                'contact' => $request->contact,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan cuti Anda telah berhasil dikirim.',
                'data' => $leaveRequest
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil riwayat pengajuan cuti milik karyawan yang sedang login.
     * @method GET
     */
    public function history()
    {
        try {
            // Logika ini juga perlu diperbaiki untuk konsistensi
            $user = Auth::user();
            $employeeId = $user->employee_id;

            if (!$employeeId) {
                return response()->json(['status' => 'error', 'message' => 'Data karyawan tidak ditemukan.'], 404);
            }

            $leaveRequests = LeaveRequest::where('employee_id', $employeeId)
                ->latest()
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Riwayat pengajuan cuti berhasil diambil.',
                'data' => $leaveRequests
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(LeaveRequest $leaveRequest)
    {
        // Keamanan: Pastikan karyawan hanya bisa melihat detail pengajuannya sendiri.
        if ($leaveRequest->employee_id !== Auth::user()->employee_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $leaveRequest
        ], 200);
    }
}
