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
        if (!Schema::hasTable('default_gajis')) {
            Schema::create('default_gajis', function (Blueprint $table) {
                $table->id();
                $table->string('gaji_nama');
                $table->foreignId('mode_id')->constrained('mode_gajis');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_gajis');
    }
};
