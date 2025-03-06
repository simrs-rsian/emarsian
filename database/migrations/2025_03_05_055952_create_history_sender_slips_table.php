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
        Schema::create('history_sender_slips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('slip_penggajian_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status');
            $table->string('message')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_sender_slips');
    }
};
