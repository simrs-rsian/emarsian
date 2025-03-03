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
        // Ubah nama tabel
        Schema::rename('slip_gajis', 'rincian_slip_gajis');

        // Ubah nama kolom dan hapus kolom
        Schema::table('rincian_slip_gajis', function (Blueprint $table) {
            $table->renameColumn('employee_id', 'slip_penggajian_id'); // Ubah nama kolom
            $table->dropColumn(['bulan', 'tahun']); // Hapus kolom
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan perubahan nama tabel
        Schema::rename('rincian_slip_gajis', 'slip_gajis');

        // Kembalikan perubahan nama kolom dan tambahkan kembali kolom yang dihapus
        Schema::table('slip_gajis', function (Blueprint $table) {
            $table->renameColumn('slip_penggajian_id', 'employee_id'); // Kembalikan nama kolom
            $table->integer('bulan'); // Tambahkan kembali kolom bulan
            $table->integer('tahun'); // Tambahkan kembali kolom tahun
        });
    }
};
