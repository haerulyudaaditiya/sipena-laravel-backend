<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman form pengaturan.
     */
    public function index()
    {
        // Ambil baris pertama dari tabel settings, atau buat jika belum ada.
        $settings = Setting::firstOrCreate([]);
        
        return view('settings.index', compact('settings'));
    }

    /**
     * Memperbarui data pengaturan.
     */
    public function update(Request $request)
    {
        // --- AWAL PERUBAHAN ---
        // DIUBAH: Aturan validasi untuk waktu diubah agar menerima detik
        $request->validate([
            'office_latitude' => 'nullable|numeric',
            'office_longitude' => 'nullable|numeric',
            'presence_radius' => 'required|integer|min:10',
            'check_in_time' => 'required|date_format:H:i,H:i:s',
            'check_out_time' => 'required|date_format:H:i,H:i:s',
            'late_tolerance' => 'required|integer|min:0',
            'annual_leave_quota' => 'required|integer|min:0',
        ]);
        // --- AKHIR PERUBAHAN ---

        // Cari pengaturan yang ada, atau buat baru jika tabel kosong.
        $settings = Setting::firstOrCreate([]);
        
        // Update data dengan yang baru dari form.
        $settings->update($request->all());

        return redirect()->route('settings.index')->with('success', 'Pengaturan perusahaan berhasil diperbarui.');
    }
}
