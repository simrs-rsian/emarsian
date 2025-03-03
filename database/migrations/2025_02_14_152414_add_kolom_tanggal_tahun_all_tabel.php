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
        $tables = [
            'slip_gajis', 'slip_potongans'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
            $table->integer('bulan')->nullable()->before('deleted_at');
            $table->integer('tahun')->nullable()->before('deleted_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'slip_gajis', 'slip_potongans'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['bulan', 'tahun']);
            });
        }
    }
};
