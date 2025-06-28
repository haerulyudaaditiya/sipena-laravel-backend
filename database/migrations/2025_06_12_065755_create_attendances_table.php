<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();  // ID unik untuk presensi
            $table->uuid('employee_id');    // ID karyawan yang melakukan presensi
            $table->timestamp('check_in')->nullable();  // Waktu check-in
            $table->timestamp('check_out')->nullable();  // Waktu check-out (nullable)

            // Kolom untuk lokasi dan foto check-in
            $table->string('check_in_location')->nullable();  // Lokasi saat check-in
            $table->decimal('check_in_latitude', 10, 7)->nullable();  // Latitude check-in
            $table->decimal('check_in_longitude', 10, 7)->nullable(); // Longitude check-in
            $table->string('check_in_photo_url')->nullable();  // URL foto saat check-in

            // Kolom untuk lokasi dan foto check-out
            $table->string('check_out_location')->nullable();  // Lokasi saat check-out
            $table->decimal('check_out_latitude', 10, 7)->nullable();  // Latitude check-out
            $table->decimal('check_out_longitude', 10, 7)->nullable(); // Longitude check-out
            $table->string('check_out_photo_url')->nullable();  // URL foto saat check-out

            $table->timestamps();  // Timestamps untuk created_at dan updated_at

            // Menambahkan Foreign Key untuk referensi ke tabel employees
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
