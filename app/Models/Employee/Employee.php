<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Employee extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nik_karyawan', 'nip_karyawan', 'photo', 'password', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir',
        'tanggal_lahir', 'tmt', 'tmta', 'masa_kerja', 'pendidikan','jurusan', 'profesi',
        'pendidikan_diakui', 'status_karyawan', 'status_keluarga', 'jabatan_struktural',
        'golongan', 'alamat_lengkap', 'telepon', 'photo', 'kelompok_usia', 'umur', 'telepon', 'golongan_darah', 'bpjs_kesehatan', 'bpjs_ketenagakerjaan', 'npwp'
    ];

    // Relationships
    public function profesi()
    {
        return $this->belongsTo('App\Models\Master\Profesi', 'profesi');
    }

    public function pendidikan()
    {
        return $this->belongsTo('App\Models\Master\Pendidikan', 'pendidikan', 'id');
    }

    public function statusKaryawan()
    {
        return $this->belongsTo('App\Models\Master\StatusKaryawan', 'status_karyawan', 'id');
    }

    public function statusKeluarga()
    {
        return $this->belongsTo('App\Models\Master\StatusKeluarga', 'status_keluarga', 'id');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Master\Unit', 'jabatan_struktural', 'id');
    }

    public function golongan()
    {
        return $this->belongsTo('App\Models\Master\Golongan', 'golongan', 'id');
    }
}
