<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceReport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Attendance::with('employee')
            ->whereBetween('check_in', [$this->startDate, $this->endDate])
            ->orderBy('check_in', 'asc')
            ->get();
    }

    /**
     * Mendefinisikan judul untuk setiap kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            'NIK',
            'Nama Karyawan',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Durasi Kerja',
            'Lokasi Check-in',
            'Lokasi Check-out',
        ];
    }

    /**
     * Memetakan data untuk setiap baris di file Excel.
     */
    public function map($attendance): array
    {
        $checkIn = \Carbon\Carbon::parse($attendance->check_in);
        $checkOut = $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out) : null;
        $duration = $checkOut ? $checkOut->diff($checkIn)->format('%H jam %i menit') : 'N/A';

        return [
            $attendance->employee->employee_id,
            $attendance->employee->name,
            $checkIn->format('d-m-Y'),
            $checkIn->format('H:i:s'),
            $checkOut ? $checkOut->format('H:i:s') : 'N/A',
            $duration,
            $attendance->check_in_location,
            $attendance->check_out_location ?? 'N/A',
        ];
    }
}
