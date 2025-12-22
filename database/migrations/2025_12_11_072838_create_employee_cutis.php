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
        // Buat tabel hanya jika belum ada
        if (!Schema::hasTable('employee_cutis')) {

            Schema::create('employee_cutis', function (Blueprint $table) {
                $table->id();
                $table->integer('employee_id');
                $table->string('tahun');
                $table->enum('periode', ['1', '2']);
                $table->integer('jumlah_cuti')->default(6);
                $table->integer('sisa_cuti')->default(12);
                $table->timestamps();
            });

        }
    }

    public function down(): void
    {
        if (Schema::hasTable('employee_cutis')) {
            Schema::dropIfExists('employee_cutis');
        }
    }
};
