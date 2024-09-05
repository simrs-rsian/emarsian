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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik_karyawan')->unique(); // NIK karyawan
            $table->string('password')->unique(); // NIK karyawan
            $table->string('nama_lengkap'); // NAMA lengkap KARYAWAN
            $table->enum('jenis_kelamin', ['L', 'P']); // Jenis Kelamin (L = Laki-laki, P = Perempuan)
            $table->string('tempat_lahir'); // TEMPAT LAHIR
            $table->date('tanggal_lahir'); // TANGGAL LAHIR
            $table->date('tmt'); // TMT (Tanggal Mulai Tugas)
            $table->date('tmta'); // TMT (Tanggal Mulai Tugas Akhir)
            $table->string('masa_kerja')->nullable(); // MASA kerja (Dalam tahun)
            $table->string('pendidikan'); // PENDIDIKAN
            $table->string('profesi'); // PROFESI
            $table->string('pendidikan_diakui')->nullable(); // PENDIDIKAN yang diakui
            $table->string('status_karyawan'); // STATUS karyawan
            $table->string('status_keluarga'); // STATUS keluarga
            $table->string('jabatan_struktural'); // JABATAN STRUKTURAL
            $table->string('golongan'); // GOLongan
            $table->text('alamat_lengkap'); // ALAMAT lengkap
            $table->string('kelompok_usia'); // KELOMPOK Usia
            $table->integer('umur'); // UMUR (Dalam tahun)
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
