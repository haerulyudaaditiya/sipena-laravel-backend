<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Membuat pengguna admin langsung
        User::create([
            'id' => (string) \Str::uuid(), // UUID untuk ID
            'name' => 'Admin User', // Nama admin pertama
            'email' => 'admin@example.com', // Email admin
            'password' => Hash::make('admin12345'), // Password admin yang sudah di-hash
            'role' => 'admin', // Menetapkan role sebagai admin
            'email_verified_at' => now(), // Set email sebagai terverifikasi
        ]);
    }
}
