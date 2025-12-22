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
        $tables = ['slip_gajis', 'slip_potongans'];

        foreach ($tables as $tableName) {

            if (!Schema::hasTable($tableName)) {
                continue; // Lewati jika tabel tidak ada
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {

                // Tambah kolom bulan jika belum ada
                if (!Schema::hasColumn($tableName, 'bulan')) {
                    if (Schema::hasColumn($tableName, 'deleted_at')) {
                        $table->integer('bulan')->nullable()->before('deleted_at');
                    } else {
                        $table->integer('bulan')->nullable();
                    }
                }

                // Tambah kolom tahun jika belum ada
                if (!Schema::hasColumn($tableName, 'tahun')) {
                    if (Schema::hasColumn($tableName, 'deleted_at')) {
                        $table->integer('tahun')->nullable()->before('deleted_at');
                    } else {
                        $table->integer('tahun')->nullable();
                    }
                }

            });
        }
    }

    public function down(): void
    {
        $tables = ['slip_gajis', 'slip_potongans'];

        foreach ($tables as $tableName) {

            if (!Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {

                if (Schema::hasColumn($tableName, 'bulan')) {
                    $table->dropColumn('bulan');
                }

                if (Schema::hasColumn($tableName, 'tahun')) {
                    $table->dropColumn('tahun');
                }

            });
        }
    }

};
