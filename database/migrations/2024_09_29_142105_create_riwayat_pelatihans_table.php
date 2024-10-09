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
        Schema::create('riwayat_pelatihans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_employee');
            $table->string('mulai');
            $table->string('selesai');
            $table->string('penyelenggara');
            $table->string('lokasi');
            $table->string('dokumen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pelatihans');
    }
};
