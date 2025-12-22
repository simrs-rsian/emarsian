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
        if (!Schema::hasTable('data_cutis')) {
            Schema::create('data_cutis', function (Blueprint $table) {
                $table->string('kode_cuti')->primary(); // ganti kode_cuti() menjadi string()
                $table->string('id_employee');
                $table->string('id_jenis_cuti');
                $table->date('tanggal_mulai_cuti');
                $table->date('tanggal_selesai_cuti');
                $table->integer('jumlah_hari_cuti');
                $table->string('alasan_cuti');
                $table->string('karyawan_pengganti')->nullable();
                $table->string('ttd_karyawan_pemohon')->nullable();
                $table->string('ttd_karyawan_pengganti')->nullable();
                $table->string('menyetujui')->nullable();
                $table->string('ttd_menyetujui')->nullable();
                $table->string('mengetahui')->nullable();
                $table->string('ttd_mengetahui')->nullable();
                $table->string('pencatat')->nullable();
                $table->string('ttd_pencatat')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('data_cutis')) {
            Schema::dropIfExists('data_cutis');
        }
    }

};
