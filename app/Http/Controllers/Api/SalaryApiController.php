<?php

namespace App\Http\Controllers\Api;

use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Import library PDF

class SalaryApiController extends Controller
{
    /**
     * Mengambil daftar riwayat gaji untuk karyawan yang login.
     */
    public function index()
    {
        $employeeId = Auth::user()->employee_id;
        $salaries = Salary::where('employee_id', $employeeId)
            ->select('id', 'salary_date', 'basic_salary', 'bonus', 'deductions', 'allowances')
            ->latest('salary_date')
            ->get()
            ->map(function ($salary) {
                // Tambahkan total gaji bersih
                $salary->net_salary = $salary->basic_salary + $salary->allowances + $salary->bonus - $salary->deductions;
                return $salary;
            });

        return response()->json(['data' => $salaries]);
    }

    /**
     * Generate dan download slip gaji dalam bentuk PDF.
     */
    public function generateDownloadLink(Salary $salary)
    {
        // Pastikan karyawan hanya bisa membuat link untuk slip gajinya sendiri
        if ($salary->employee_id !== Auth::user()->employee_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Buat URL yang ditandatangani dan berlaku selama 5 menit
        $temporaryUrl = URL::temporarySignedRoute(
            'salaries.download',
            now()->addMinutes(5),
            ['salary' => $salary->id]
        );

        return response()->json(['download_url' => $temporaryUrl]);
    }

    /**
     * Generate dan download slip gaji dalam bentuk PDF.
     * Otentikasi kini ditangani oleh middleware 'signed'.
     */
    public function downloadPayslip(Request $request, Salary $salary) // Request tidak wajib, tapi baik untuk ada
    {
        // DIHAPUS: Blok validasi otentikasi dihapus karena sudah ditangani oleh middleware 'signed'
        // if ($salary->employee_id !== Auth::user()->employee_id) { ... }

        // Pastikan request memiliki signature yang valid (keamanan tambahan)
        if (! $request->hasValidSignature()) {
            abort(401, 'Invalid signature.');
        }

        // Hitung total gaji untuk ditampilkan di PDF
        $salary->net_salary = $salary->basic_salary + $salary->allowances + $salary->bonus - $salary->deductions;

        $pdf = Pdf::loadView('pdf.payslip', ['salary' => $salary]);
        $fileName = 'slip-gaji-' . \Carbon\Carbon::parse($salary->salary_date)->format('F-Y') . '.pdf';

        return $pdf->download($fileName);
    }
}
