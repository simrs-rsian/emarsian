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
        Schema::table('history_sender_slips', function (Blueprint $table) {
            $table->string('status_downloader')->after('link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_sender_slips', function (Blueprint $table) {
            $table->dropColumn('status_downloader');
        });
    }
};
