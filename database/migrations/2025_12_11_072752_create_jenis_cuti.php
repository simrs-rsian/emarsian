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
        if (!Schema::hasTable('jenis_cutis')) {

            Schema::create('jenis_cutis', function (Blueprint $table) {
                $table->id();
                $table->string('nama_jenis_cuti');
                $table->timestamps();
            });

        }
    }

    public function down(): void
    {
        if (Schema::hasTable('jenis_cutis')) {
            Schema::dropIfExists('jenis_cutis');
        }
    }
};
