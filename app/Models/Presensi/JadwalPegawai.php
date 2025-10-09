<?php

namespace App\Models\Presensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPegawai extends Model
{
    use HasFactory;
    //TABEL diambil dari DB::connection('mysql2')
    protected $connection = 'mysql2';
    protected $table = 'jadwal_pegawai';
    protected $guarded = [];
    public $timestamps = false;
}
