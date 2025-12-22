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
        if (Schema::hasTable('web_settings')) {
            Schema::table('web_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('web_settings', 'name')) {
                    $table->string('name')->nullable()->after('id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('web_settings')) {
            Schema::table('web_settings', function (Blueprint $table) {
                if (Schema::hasColumn('web_settings', 'name')) {
                    $table->dropColumn('name');
                }
            });
        }
    }

};
