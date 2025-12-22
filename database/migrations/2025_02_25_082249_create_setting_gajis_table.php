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
        // Cegah error jika tabel sudah ada
        if (!Schema::hasTable('setting_gajis')) {

            // Pastikan tabel default_gajis tersedia sebelum membuat foreign key
            if (Schema::hasTable('default_gajis')) {
                Schema::create('setting_gajis', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('default_gaji_id')->constrained('default_gajis');
                    $table->integer('employee_id');
                    $table->integer('nominal');
                    $table->timestamps();
                });

            } else {
                // Jika default_gajis belum ada, buat tabel tanpa FK agar tidak error
                Schema::create('setting_gajis', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('default_gaji_id'); // tanpa FK
                    $table->integer('employee_id');
                    $table->integer('nominal');
                    $table->timestamps();
                });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('setting_gajis');
    }

};
