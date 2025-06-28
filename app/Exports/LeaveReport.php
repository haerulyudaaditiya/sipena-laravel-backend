<?php

namespace App\Exports;

use App\Models\LeaveRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LeaveReport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return LeaveRequest::with('employee')
            ->whereBetween('start_date', [$this->startDate, $this->endDate])
            ->get();
    }

    public function headings(): array
    {
        return ["NIK", "Nama Karyawan", "Jenis Cuti", "Tanggal Mulai", "Tanggal Selesai", "Durasi (Hari)", "Status", "Alasan"];
    }

    public function map($leave): array
    {
        $start = \Carbon\Carbon::parse($leave->start_date);
        $end = \Carbon\Carbon::parse($leave->end_date);
        $duration = $end->diffInDays($start) + 1;

        switch ($leave->type) {
            case 'annual': $type = 'Cuti Tahunan'; break;
            case 'sick': $type = 'Izin Sakit'; break;
            case 'personal': $type = 'Keperluan Pribadi'; break;
            default: $type = 'Lainnya';
        }

        return [
            $leave->employee->employee_id,
            $leave->employee->name,
            $type,
            $leave->start_date,
            $leave->end_date,
            $duration,
            ucfirst($leave->status),
            $leave->reason,
        ];
    }
}
