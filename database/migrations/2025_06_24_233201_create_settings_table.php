<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // Lokasi Kantor
            $table->string('office_latitude')->nullable();
            $table->string('office_longitude')->nullable();
            $table->integer('presence_radius')->default(100); // dalam meter

            // Jam Kerja
            $table->time('check_in_time')->default('08:00');
            $table->time('check_out_time')->default('16:00');
            $table->integer('check_in_start_margin')->default(15);

            // Kebijakan Cuti
            $table->integer('annual_leave_quota')->default(12); // dalam hari

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
