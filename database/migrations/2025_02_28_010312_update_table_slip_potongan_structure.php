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
        // Rename tabel hanya jika tujuan belum ada
        if (Schema::hasTable('slip_potongans') && !Schema::hasTable('rincian_slip_potongans')) {
            Schema::rename('slip_potongans', 'rincian_slip_potongans');
        }

        // Pastikan tabel tujuan ada
        if (Schema::hasTable('rincian_slip_potongans')) {
            Schema::table('rincian_slip_potongans', function (Blueprint $table) {
                
                // Rename kolom jika kolom asal ada dan kolom tujuan belum ada
                if (Schema::hasColumn('rincian_slip_potongans', 'employee_id') &&
                    !Schema::hasColumn('rincian_slip_potongans', 'slip_penggajian_id')) {
                    $table->renameColumn('employee_id', 'slip_penggajian_id');
                }

                // Hapus kolom hanya jika kolomnya memang ada
                if (Schema::hasColumn('rincian_slip_potongans', 'bulan')) {
                    $table->dropColumn('bulan');
                }
                if (Schema::hasColumn('rincian_slip_potongans', 'tahun')) {
                    $table->dropColumn('tahun');
                }
            });
        }
    }

    public function down(): void
    {
        // Kembalikan nama tabel jika kondisi memungkinkan
        if (Schema::hasTable('rincian_slip_potongans') && !Schema::hasTable('slip_potongans')) {
            Schema::rename('rincian_slip_potongans', 'slip_potongans');
        }

        if (Schema::hasTable('slip_potongans')) {
            Schema::table('slip_potongans', function (Blueprint $table) {

                if (Schema::hasColumn('slip_potongans', 'slip_penggajian_id') &&
                    !Schema::hasColumn('slip_potongans', 'employee_id')) {
                    $table->renameColumn('slip_penggajian_id', 'employee_id');
                }

                // Tambahkan kolom jika belum ada
                if (!Schema::hasColumn('slip_potongans', 'bulan')) {
                    $table->integer('bulan');
                }
                if (!Schema::hasColumn('slip_potongans', 'tahun')) {
                    $table->integer('tahun');
                }
            });
        }
    }

};
