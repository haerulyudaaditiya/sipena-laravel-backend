<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\SendOtpMail; // DITAMBAHKAN: Import Mailable yang baru
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Meminta pengiriman kode OTP ke email pengguna.
     */
    public function requestOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Hapus OTP lama jika ada
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Buat OTP baru (6 digit angka)
        $otp = rand(100000, 999999);

        // Simpan OTP ke database
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $otp,
            'created_at' => Carbon::now()
        ]);

        // DIUBAH: Mengirim email menggunakan Mailable class
        try {
            Mail::to($request->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            // Tambahkan logging untuk debug jika perlu
            // Log::error('Mail sending failed: '.$e->getMessage());
            return response()->json(['message' => 'Gagal mengirim email OTP. Mohon periksa konfigurasi email Anda.'], 500);
        }
        
        return response()->json(['message' => 'Kode OTP telah berhasil dikirim ke email Anda.']);
    }

    /**
     * Mereset password menggunakan OTP.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric|digits:6',
            'password' => 'required|string|min:8',
        ]);

        // Cari token OTP di database
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        // Cek jika token tidak ada atau sudah kedaluwarsa (lebih dari 10 menit)
        if (!$tokenData || Carbon::parse($tokenData->created_at)->addMinutes(10)->isPast()) {
            return response()->json(['message' => 'Kode OTP tidak valid atau telah kedaluwarsa.'], 422);
        }

        // Cari user dan update passwordnya
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token setelah berhasil digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password Anda telah berhasil direset.']);
    }
}
