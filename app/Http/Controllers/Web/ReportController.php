<?php

namespace App\Http\Controllers\Web;

use App\Exports\AttendanceReport;
use App\Exports\LeaveReport; // 1. Import LeaveReport
use App\Exports\SalaryReport; // 2. Import SalaryReport
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function exportAttendance(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $fileName = 'laporan-kehadiran-' . $request->start_date . '-sampai-' . $request->end_date . '.xlsx';
        return Excel::download(new AttendanceReport($request->start_date, $request->end_date), $fileName);
    }

    /**
     * DITAMBAHKAN: Memproses permintaan dan men-download laporan cuti.
     */
    public function exportLeave(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $fileName = 'laporan-cuti-' . $request->start_date . '-sampai-' . $request->end_date . '.xlsx';
        return Excel::download(new LeaveReport($request->start_date, $request->end_date), $fileName);
    }

    /**
     * DITAMBAHKAN: Memproses permintaan dan men-download laporan gaji.
     */
    public function exportSalary(Request $request)
    {
        $request->validate([
            'month' => 'required|numeric|between:1,12',
            'year' => 'required|numeric|digits:4',
        ]);
        $dateObj = \DateTime::createFromFormat('!m', $request->month);
        $monthName = $dateObj->format('F');
        $fileName = 'laporan-gaji-' . $monthName . '-' . $request->year . '.xlsx';
        return Excel::download(new SalaryReport($request->month, $request->year), $fileName);
    }
}
