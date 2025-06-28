<?php

namespace App\Http\Controllers\Api;

use App\Helpers\LocationHelper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AttendanceApiController extends Controller
{
    /**
     * Check-in employee
     * @method POST
     */
    public function checkIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string|exists:employees,id',
            'check_in_location' => 'required|string',
            'check_in_latitude' => 'required|numeric',
            'check_in_longitude' => 'required|numeric',
            'check_in_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        try {
            // Check if already checked-in today
            $existingAttendance = Attendance::where('employee_id', $request->employee_id)
                ->whereDate('check_in', today())
                ->first();

            if ($existingAttendance) {
                return response()->json(['status' => 'error', 'message' => 'Anda sudah check-in hari ini'], 400);
            }

            // --- AWAL LOGIKA VALIDASI ---
            $status = 'Tepat Waktu';
            $checkInTime = Carbon::now();
            $settings = Setting::first();

            if ($settings) {
                // 1. Validasi Jarak (Geofencing)
                if ($settings->office_latitude && $settings->office_longitude) {
                    $distance = LocationHelper::distance(
                        $request->check_in_latitude,
                        $request->check_in_longitude,
                        $settings->office_latitude,
                        $settings->office_longitude
                    );

                    if ($distance > $settings->presence_radius) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Anda berada di luar radius presensi yang diizinkan. Jarak Anda dari kantor: ' . round($distance) . ' meter.'
                        ], 400);
                    }
                }

                // 2. Validasi Keterlambatan
                if ($settings->check_in_time) {
                    $officeCheckInTime = Carbon::parse($settings->check_in_time);

                    // DIHAPUS: Validasi waktu mulai absen tidak lagi digunakan
                    // $earliestCheckInTime = $officeCheckInTime->copy()->subMinutes($settings->check_in_start_margin);
                    // if ($checkInTime->isBefore($earliestCheckInTime)) { ... }

                    // Cek keterlambatan (logika ini tetap ada)
                    if ($checkInTime->gt($officeCheckInTime->addMinutes($settings->late_tolerance))) {
                        $status = 'Terlambat';
                    }
                }
            }
            // --- AKHIR LOGIKA VALIDASI ---

            // Upload check-in photo (Logika penyimpanan foto tidak diubah)
            $photoPath = $request->file('check_in_photo')->store('attendance/check-in', 'public');

            $attendance = Attendance::create([
                'employee_id' => $request->employee_id,
                'check_in' => $checkInTime,
                'check_in_location' => $request->check_in_location,
                'check_in_latitude' => $request->check_in_latitude,
                'check_in_longitude' => $request->check_in_longitude,
                'check_in_photo_url' => Storage::url($photoPath),
                'status' => $status, // Menyimpan status hasil validasi
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Check-in successful',
                'attendance' => $attendance
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Check-out employee
     * @method POST
     */
    public function checkOut(Request $request, $attendanceId)
    {
        // ... (Logika checkOut Anda tetap sama, tidak diubah)
        $validator = Validator::make($request->all(), [
            'check_out_location' => 'nullable|string',
            'check_out_latitude' => 'nullable|numeric',
            'check_out_longitude' => 'nullable|numeric',
            'check_out_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }
        try {
            $attendance = Attendance::where('id', $attendanceId)->whereNull('check_out')->firstOrFail();
            $photoPath = $request->file('check_out_photo')->store('attendance/check-out', 'public');
            $attendance->check_out = now();
            $attendance->check_out_location = $request->check_out_location;
            $attendance->check_out_latitude = $request->check_out_latitude;
            $attendance->check_out_longitude = $request->check_out_longitude;
            $attendance->check_out_photo_url = Storage::url($photoPath);
            $attendance->save();
            return response()->json(['status' => 'success', 'message' => 'Check-out successful', 'attendance' => $attendance], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => 'Data presensi tidak ditemukan atau sudah check-out'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function history()
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->employee_id) {
                return response()->json(['status' => 'error', 'message' => 'Data karyawan tidak terhubung dengan akun user ini.'], 404);
            }

            $attendances = Attendance::where('employee_id', $user->employee_id)
                ->latest('check_in') // Urutkan dari yang terbaru
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Riwayat absensi berhasil diambil.',
                'data' => $attendances
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Attendance $attendance)
    {
        // Keamanan: Pastikan karyawan hanya bisa melihat detail absensinya sendiri.
        if ($attendance->employee_id !== Auth::user()->employee_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Jika Anda ingin menambahkan data lain, bisa dilakukan di sini.
        // Contoh: $attendance->load('employee:id,name');

        return response()->json([
            'status' => 'success',
            'data' => $attendance
        ], 200);
    }

    public function checkCurrentStatus()
    {
        $user = Auth::user();
        if (!$user || !$user->employee_id) {
            return response()->json(['status' => 'error', 'message' => 'Karyawan tidak ditemukan.'], 404);
        }

        // Cari data check-in untuk karyawan ini PADA HARI INI
        $attendance = Attendance::where('employee_id', $user->employee_id)
            ->whereDate('check_in', today())
            ->first();

        // Kirim data absensi jika ditemukan, jika tidak, kirim data kosong
        return response()->json([
            'status' => 'success',
            'data' => $attendance // Akan menjadi null jika tidak ditemukan
        ]);
    }
}
