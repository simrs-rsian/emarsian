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
        if (!Schema::hasTable('slip_penggajians')) {
            Schema::create('slip_penggajians', function (Blueprint $table) {
                $table->id();
                $table->integer('bulan');
                $table->integer('tahun');
                $table->bigInteger('total_gaji');
                $table->bigInteger('total_potongan');
                $table->bigInteger('total_terima');

                // Tambahkan FK hanya jika tabel employees ada
                if (Schema::hasTable('employees')) {
                    $table->foreignId('employee_id')->constrained('employees');
                } else {
                    $table->unsignedBigInteger('employee_id');
                }

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('slip_penggajians')) {
            Schema::dropIfExists('slip_penggajians');
        }
    }

};
