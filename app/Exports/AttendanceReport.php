<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;   // 2. Import event AfterSheet
use Maatwebsite\Excel\Concerns\WithEvents; // 1. Import interface WithEvents

class AttendanceReport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithEvents // 3. Terapkan interface
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
        return Attendance::with('employee')
            ->whereBetween('check_in', [$this->startDate, $this->endDate])
            ->orderBy('check_in', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Karyawan',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Durasi Kerja',
            'Status',
            'Lokasi Check-in',
            'Lokasi Check-out',
        ];
    }

    public function columnFormats(): array
    {
        return ['A' => NumberFormat::FORMAT_TEXT];
    }

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
            $attendance->status,
            $attendance->check_in_location,
            $attendance->check_out_location ?? 'N/A',
        ];
    }

    /**
     * DITAMBAHKAN: Fungsi untuk mendaftarkan event.
     * Kode di sini akan berjalan setelah sheet selesai dibuat, sebelum diubah ke PDF.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // 1. Mengatur orientasi halaman menjadi Landscape
                $event->sheet->getDelegate()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                // 2. DITAMBAHKAN: Mengatur ukuran kertas menjadi lebih lebar (misal: A3 atau Legal)
                // Ini akan memberikan lebih banyak ruang horizontal.
                $event->sheet->getDelegate()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LEGAL);

                // 3. DIUBAHKAN: Memastikan setFitToWidth diaktifkan
                // Ini akan memaksa semua kolom agar muat dalam satu halaman lebar.
                $event->sheet->getDelegate()->getPageSetup()->setFitToWidth(1);
                $event->sheet->getDelegate()->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
