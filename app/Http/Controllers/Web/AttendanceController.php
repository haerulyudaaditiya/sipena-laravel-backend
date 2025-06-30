<?php

namespace App\Http\Controllers\Web;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query dasar
        $query = Attendance::with('employee')->latest('check_in');

        // Terapkan filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Terapkan filter berdasarkan rentang tanggal jika keduanya diisi
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('check_in', [$request->start_date, $request->end_date]);
        }

        // Ambil data dengan paginasi
        $attendances = $query->get();

        return view('attendances.index', compact('attendances'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'nullable|date|after_or_equal:check_in',
        ], [
            'check_out.after_or_equal' => 'Waktu check-out tidak boleh lebih awal dari waktu check-in.'
        ]);

        $attendance->update([
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
        ]);

        return redirect()->route('attendances.index')->with('success', 'Waktu kehadiran berhasil diperbarui.');
    }

    public function destroy(Attendance $attendance)
    {
        // Menghapus data attendance
        $attendance->delete();
        return redirect()->route('attendances.index')->with('success', 'Data kehadiran berhasil dihapus.');
    }
}
