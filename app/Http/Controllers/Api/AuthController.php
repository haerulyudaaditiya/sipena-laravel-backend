<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Login
    public function login(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Cek apakah email terdaftar
        $user = User::where('email', $validated['email'])->first();

        // Pastikan user ditemukan dan password cocok
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan salah.'],
            ]);
        }

        // Pastikan status karyawan di tabel 'employee' adalah 'active'
        $employee = $user->employee; // Akses relasi employee
        if (!$employee || $employee->status !== 'active') {
            throw ValidationException::withMessages([
                'status' => ['Akun Anda tidak aktif. Silakan hubungi administrator Anda.'],
            ]);
        }

        // Pastikan role user adalah 'employee'
        if ($user->role !== 'employee') {
            throw ValidationException::withMessages([
                'role' => ['Hanya karyawan yang diizinkan masuk.'],
            ]);
        }

        // Generate token untuk user yang aktif dan memiliki role 'employee'
        $token = $user->createToken('IonicApp')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        // Pastikan pengguna sudah login (memiliki token)
        $request->user()->tokens->each(function ($token) {
            // Hapus semua token yang ada
            $token->delete();
        });

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    public function changePassword(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Mendapatkan user yang sedang login
        $user = $request->user();

        // Cek apakah password lama sesuai dengan yang ada di database
        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Kata sandi saat ini salah.'],
            ]);
        }

        // Update password dengan password baru
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Kata sandi berhasil diubah',
        ]);
    }
}
