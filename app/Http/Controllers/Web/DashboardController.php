<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // === 1. Statistik Small Boxes ===
        $totalKaryawan = Employee::count();
        $hadirHariIni = Attendance::whereDate('check_in', today())->count();
        $cutiPending = LeaveRequest::where('status', 'pending')->count();
        
        // Total gaji yang dibayarkan bulan ini
        $totalGajiBulanIni = Salary::whereYear('salary_date', now()->year)
            ->whereMonth('salary_date', now()->month)
            ->sum(DB::raw('basic_salary + allowances + bonus - deductions'));

        
        // === 2. Data untuk Grafik Gaji (6 Bulan Terakhir) ===
        $salaryData = Salary::select(
                DB::raw('YEAR(salary_date) as year, MONTH(salary_date) as month'),
                DB::raw('SUM(basic_salary + allowances + bonus - deductions) as total_paid')
            )
            ->where('salary_date', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $salaryLabels = $salaryData->map(function ($item) {
            return Carbon::createFromDate($item->year, $item->month)->format('F Y');
        });
        $salaryValues = $salaryData->pluck('total_paid');


        // === 3. Data untuk Grafik Tipe Cuti (Bulan Ini) ===
        $leaveData = LeaveRequest::select('type', DB::raw('count(*) as total'))
            ->whereYear('start_date', now()->year)
            ->whereMonth('start_date', now()->month)
            ->groupBy('type')
            ->get();

        $leaveLabels = $leaveData->pluck('type')->map(function($type){
            // Terjemahkan ke Bahasa Indonesia
            switch ($type) {
                case 'annual': return 'Tahunan';
                case 'sick': return 'Sakit';
                case 'personal': return 'Pribadi';
                default: return 'Lainnya';
            }
        });
        $leaveValues = $leaveData->pluck('total');


        // === 4. Daftar Pengajuan Cuti Terbaru yang Masih Pending ===
        $recentLeaveRequests = LeaveRequest::with('employee')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();


        // Kirim semua data ke view
        return view('dashboard', compact(
            'totalKaryawan',
            'hadirHariIni',
            'cutiPending',
            'totalGajiBulanIni',
            'salaryLabels',
            'salaryValues',
            'leaveLabels',
            'leaveValues',
            'recentLeaveRequests'
        ));
    }
}
