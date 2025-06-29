<?php

namespace App\Exports;

use App\Models\LeaveRequest;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Events\AfterSheet;   // 2. Import event AfterSheet
use Maatwebsite\Excel\Concerns\WithEvents; // 1. Import interface WithEvents
use Maatwebsite\Excel\Concerns\WithColumnFormatting; // DITAMBAHKAN: Untuk format kolom
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;     // DITAMBAHKAN: Untuk konstanta format


class LeaveReport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithEvents
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
            ->orderBy('start_date', 'asc') // Urutkan berdasarkan tanggal mulai
            ->get();
    }

    public function headings(): array
    {
        return ["NIK", "Nama Karyawan", "Jenis Cuti", "Tanggal Mulai", "Tanggal Selesai", "Durasi (Hari)", "Status", "Alasan"];
    }

    /**
     * DITAMBAHKAN: Fungsi untuk memaksa kolom NIK menjadi format Teks.
     * Ini akan mencegah Excel mengubahnya menjadi notasi ilmiah.
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }

    /**
     * DIUBAH: Logika map diperbarui untuk menerjemahkan status.
     */
    public function map($leave): array
    {
        $start = \Carbon\Carbon::parse($leave->start_date);
        $end = \Carbon\Carbon::parse($leave->end_date);

        // Menggunakan diffInDays() dengan nilai absolut, lalu ditambah 1 untuk perhitungan inklusif
        $duration = $start->diffInDays($end, true) + 1;

        // Logika untuk menerjemahkan tipe cuti
        $type = 'Lainnya';
        switch ($leave->type) {
            case 'annual':
                $type = 'Cuti Tahunan';
                break;
            case 'sick':
                $type = 'Izin Sakit';
                break;
            case 'personal':
                $type = 'Keperluan Pribadi';
                break;
        }

        // Logika untuk menerjemahkan status
        $status = 'Tidak Diketahui';
        switch ($leave->status) {
            case 'approved':
                $status = 'Disetujui';
                break;
            case 'rejected':
                $status = 'Ditolak';
                break;
            case 'pending':
                $status = 'Menunggu Persetujuan';
                break;
        }

        return [
            $leave->employee->employee_id,
            $leave->employee->name,
            $type,
            $leave->start_date,
            $leave->end_date,
            $duration,
            $status,
            $leave->reason,
        ];
    }

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
