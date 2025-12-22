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
        if (!Schema::hasTable('history_sender_slips')) {

            Schema::create('history_sender_slips', function (Blueprint $table) {
                $table->id();

                // Kolom FK (tidak langsung constrained agar aman)
                $table->unsignedBigInteger('slip_penggajian_id');
                $table->unsignedBigInteger('user_id');

                $table->string('status');
                $table->string('message')->nullable();
                $table->string('link')->nullable();

                $table->timestamps();
            });

            // Tambahkan FK hanya jika tabel referensi sudah ada
            if (Schema::hasTable('slip_penggajians')) {
                Schema::table('history_sender_slips', function (Blueprint $table) {
                    $table->foreign('slip_penggajian_id')
                        ->references('id')->on('slip_penggajians')
                        ->cascadeOnDelete();
                });
            }

            if (Schema::hasTable('users')) {
                Schema::table('history_sender_slips', function (Blueprint $table) {
                    $table->foreign('user_id')
                        ->references('id')->on('users')
                        ->cascadeOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('history_sender_slips')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('history_sender_slips');
            Schema::enableForeignKeyConstraints();
        }
    }

};
