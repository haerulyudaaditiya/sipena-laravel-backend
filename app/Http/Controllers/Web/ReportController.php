<?php

namespace App\Http\Controllers\Web;

use App\Exports\AttendanceReport;
use App\Exports\LeaveReport;
use App\Exports\SalaryReport;
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
            'export_type' => 'required|in:excel,pdf', // Validasi tipe ekspor
        ]);

        $fileName = 'laporan-kehadiran-' . $request->start_date . '-sampai-' . $request->end_date;
        $report = new AttendanceReport($request->start_date, $request->end_date);
        
        // DIUBAH: Logika untuk memilih format ekspor
        if ($request->export_type === 'pdf') {
            return Excel::download($report, $fileName . '.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }
        
        return Excel::download($report, $fileName . '.xlsx');
    }

    public function exportLeave(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'export_type' => 'required|in:excel,pdf',
        ]);
        
        $fileName = 'laporan-cuti-' . $request->start_date . '-sampai-' . $request->end_date;
        $report = new LeaveReport($request->start_date, $request->end_date);

        if ($request->export_type === 'pdf') {
            return Excel::download($report, $fileName . '.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }

        return Excel::download($report, $fileName . '.xlsx');
    }

    public function exportSalary(Request $request)
    {
        $request->validate([
            'month' => 'required|numeric|between:1,12',
            'year' => 'required|numeric|digits:4',
            'export_type' => 'required|in:excel,pdf',
        ]);

        $dateObj = \DateTime::createFromFormat('!m', $request->month);
        $monthName = $dateObj->format('F');
        $fileName = 'laporan-gaji-' . $monthName . '-' . $request->year;
        $report = new SalaryReport($request->month, $request->year);

        if ($request->export_type === 'pdf') {
            return Excel::download($report, $fileName . '.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }

        return Excel::download($report, $fileName . '.xlsx');
    }
}