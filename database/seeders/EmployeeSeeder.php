<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Password default untuk semua user karyawan yang dibuat
        $defaultPassword = Hash::make('password');

        // Data sampel untuk 10 karyawan
        $employeesData = [
            ['name' => 'Budi Santoso', 'position' => 'Software Engineer', 'department' => 'IT'],
            ['name' => 'Citra Lestari', 'position' => 'UI/UX Designer', 'department' => 'Product'],
            ['name' => 'Doni Saputra', 'position' => 'Project Manager', 'department' => 'Management'],
            ['name' => 'Eka Putri', 'position' => 'QA Tester', 'department' => 'IT'],
            ['name' => 'Fajar Nugroho', 'position' => 'DevOps Engineer', 'department' => 'IT'],
            ['name' => 'Gita Wulandari', 'position' => 'HR Staff', 'department' => 'Human Resources'],
            ['name' => 'Hadi Prawira', 'position' => 'Marketing Specialist', 'department' => 'Marketing'],
            ['name' => 'Indah Permata', 'position' => 'Finance Staff', 'department' => 'Finance'],
            ['name' => 'Joko Susilo', 'position' => 'Frontend Developer', 'department' => 'IT'],
            ['name' => 'Kartika Dewi', 'position' => 'Backend Developer', 'department' => 'IT'],
        ];

        foreach ($employeesData as $data) {
            // 1. Buat data employee baru
            $employee = Employee::create([
                'id' => (string) Str::uuid(),
                'employee_id' => 'EMP-' . random_int(10000, 99999), // NIK unik
                'name' => $data['name'],
                'email' => strtolower(str_replace(' ', '', $data['name'])) . '@example.com',
                'position' => $data['position'],
                'department' => $data['department'],
                'status' => 'active',
                'hire_date' => now()->subMonths(random_int(1, 24)), // Tanggal bergabung acak
            ]);

            // 2. Buat data user yang terhubung dengan employee
            User::create([
                'id' => (string) Str::uuid(),
                'employee_id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'password' => $defaultPassword,
                'role' => 'employee',
            ]);
        }
    }
}
