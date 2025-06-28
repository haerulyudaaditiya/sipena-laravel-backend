<?php

namespace App\Exports;

use App\Models\Salary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalaryReport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
}
