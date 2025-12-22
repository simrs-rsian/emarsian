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
        if (Schema::hasTable('history_sender_slips')) {
            Schema::table('history_sender_slips', function (Blueprint $table) {
                if (!Schema::hasColumn('history_sender_slips', 'status_downloader')) {
                    $table->string('status_downloader')->after('link');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('history_sender_slips')) {
            Schema::table('history_sender_slips', function (Blueprint $table) {
                if (Schema::hasColumn('history_sender_slips', 'status_downloader')) {
                    $table->dropColumn('status_downloader');
                }
            });
        }
    }

};
