<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik_karyawan')->unique();
            $table->string('password')->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->date('tmt');
            $table->date('tmta');
            $table->string('masa_kerja')->nullable();
            $table->string('pendidikan');
            $table->foreignId('profesi')->constrained('profesis');
            $table->foreignId('pendidikan_diakui')->nullable()->constrained('pendidikans');
            $table->foreignId('status_karyawan')->constrained('status_karyawans');
            $table->foreignId('status_keluarga')->constrained('status_keluargas');
            $table->foreignId('jabatan_struktural')->constrained('jabatans');
            $table->foreignId('golongan')->constrained('golongans');
            $table->text('alamat_lengkap');
            $table->timestamp('telepon')->nullable();
            $table->string('photo', 100)->nullable();
            $table->string('kelompok_usia');
            $table->integer('umur');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
