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
        Schema::create('slip_penggajians', function (Blueprint $table) {
            $table->id();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->bigInteger('total_gaji');
            $table->bigInteger('total_potongan');
            $table->bigInteger('total_terima');
            $table->foreignId('employee_id')->constrained('employees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slip_penggajians');
    }
};
