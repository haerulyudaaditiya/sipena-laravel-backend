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
        Schema::create('salaries', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->uuid('employee_id'); 
            $table->decimal('basic_salary', 15, 2); 
            $table->decimal('bonus', 15, 2)->nullable(); 
            $table->decimal('deductions', 15, 2)->nullable(); 
            $table->decimal('allowances', 15, 2)->nullable(); 
            $table->date('salary_date'); 
            $table->timestamps(); 

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
