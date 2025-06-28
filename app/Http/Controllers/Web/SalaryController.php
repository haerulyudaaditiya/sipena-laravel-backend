<?php

namespace App\Http\Controllers\Web;

use App\Models\Salary;
use App\Models\Employee;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User; // DITAMBAHKAN: Import model User
use Illuminate\Support\Facades\Http; // DITAMBAHKAN: Import HTTP Client Laravel

class SalaryController extends Controller
{
    /**
     * Menampilkan daftar gaji.
     */
    public function index()
    {
        $salaries = Salary::with('employee')->latest('salary_date')->paginate(20);
        $employees = Employee::orderBy('name')->get();
        
        return view('salaries.index', compact('salaries', 'employees'));
    }

    /**
     * Menyimpan data gaji baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|uuid|exists:employees,id',
            'salary_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
        ]);

        // ... (logika cek existing salary Anda)

        $salary = Salary::create($request->all());

        // --- AWAL LOGIKA PUSH NOTIFICATION ---
        try {
            // 1. Buat notifikasi di database
            $periode = \Carbon\Carbon::parse($salary->salary_date)->format('F Y');
            $title = "Slip Gaji Periode $periode Telah Terbit";
            $message = "Rincian slip gaji Anda untuk periode $periode sudah tersedia. Silakan cek di aplikasi.";
            
            Notification::create([
                'employee_id'    => $salary->employee_id,
                'title'          => $title,
                'message'        => $message,
                'status'         => 'unread',
                'related_model'  => Salary::class,
                'related_id'     => $salary->id,
            ]);

            // 2. Kirim push notification ke perangkat
            $employeeUser = User::where('employee_id', $salary->employee_id)->first();
            
            if ($employeeUser && $employeeUser->fcm_token) {
                $serverKey = env('FCM_SERVER_KEY');

                Http::withHeaders([
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
                        'related_model' => 'Salary',
                        'related_id' => $salary->id,
                    ],
                ]);
            }
        } catch (\Exception $e) {
            // Jika notifikasi gagal, proses utama tetap jalan, tapi catat errornya
            Log::error('Gagal membuat notifikasi gaji: ' . $e->getMessage());
        }
        // --- AKHIR LOGIKA PUSH NOTIFICATION ---

        return redirect()->route('salaries.index')->with('success', 'Data gaji berhasil ditambahkan.');
    }

    /**
     * Memperbarui data gaji.
     */
    public function update(Request $request, Salary $salary)
    {
        $request->validate([
            'salary_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
        ]);

        $salary->update($request->all());

        return redirect()->route('salaries.index')->with('success', 'Data gaji berhasil diperbarui.');
    }

    /**
     * Menghapus data gaji.
     */
    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('salaries.index')->with('success', 'Data gaji berhasil dihapus.');
    }
}
