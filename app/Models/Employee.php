<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees'; // Nama tabel di database

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik_karyawan',
        'password',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'tmt',
        'tmta',
        'masa_kerja',
        'pendidikan',
        'profesi',
        'pendidikan_diakui',
        'status_karyawan',
        'status_keluarga',
        'jabatan_struktural',
        'golongan',
        'alamat_lengkap',
        'kelompok_usia',
        'umur',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tmt' => 'date',
        'tmta' => 'date',
    ];
}
