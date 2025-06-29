<?php

namespace App\Exports;

use App\Models\Salary;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Concerns\WithColumnFormatting; // DITAMBAHKAN
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;     // DITAMBAHKAN
use Maatwebsite\Excel\Events\AfterSheet;   // 2. Import event AfterSheet


class SalaryReport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithEvents 
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return Salary::with('employee')
            ->whereYear('salary_date', $this->year)
            ->whereMonth('salary_date', $this->month)
            ->get();
    }

    public function headings(): array
    {
        return ["NIK", "Nama Karyawan", "Gaji Pokok", "Tunjangan", "Bonus", "Total Penerimaan", "Potongan", "Gaji Bersih"];
    }

    /**
     * DITAMBAHKAN: Memaksa kolom NIK (kolom A) menjadi format Teks.
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function map($salary): array
    {
        $totalPenerimaan = $salary->basic_salary + $salary->allowances + $salary->bonus;
        $gajiBersih = $totalPenerimaan - $salary->deductions;

        return [
            $salary->employee->employee_id,
            $salary->employee->name,
            $salary->basic_salary,
            $salary->allowances,
            $salary->bonus,
            $totalPenerimaan,
            $salary->deductions,
            $gajiBersih,
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
