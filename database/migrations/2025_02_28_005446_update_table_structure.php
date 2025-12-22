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
        $old = 'slip_gajis';
        $new = 'rincian_slip_gajis';

        // Rename hanya jika tabel lama ada DAN tabel baru belum ada
        if (Schema::hasTable($old) && !Schema::hasTable($new)) {
            Schema::rename($old, $new);
        }

        // Lanjutkan modifikasi hanya jika tabel baru ada
        if (Schema::hasTable($new)) {
            Schema::table($new, function (Blueprint $table) use ($new) {

                // Rename kolom jika ada
                if (Schema::hasColumn($new, 'employee_id')) {
                    $table->renameColumn('employee_id', 'slip_penggajian_id');
                }

                // Drop kolom yang ada
                $drop = [];
                if (Schema::hasColumn($new, 'bulan'))  $drop[] = 'bulan';
                if (Schema::hasColumn($new, 'tahun'))  $drop[] = 'tahun';
                if (!empty($drop)) {
                    $table->dropColumn($drop);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('rincian_slip_gajis')) {
            Schema::rename('rincian_slip_gajis', 'slip_gajis');
        }

        if (Schema::hasTable('slip_gajis')) {
            Schema::table('slip_gajis', function (Blueprint $table) {
                if (Schema::hasColumn('slip_gajis', 'slip_penggajian_id')) {
                    $table->renameColumn('slip_penggajian_id', 'employee_id');
                }

                if (!Schema::hasColumn('slip_gajis', 'bulan')) {
                    $table->integer('bulan')->nullable();
                }
                if (!Schema::hasColumn('slip_gajis', 'tahun')) {
                    $table->integer('tahun')->nullable();
                }
            });
        }
    }

};
