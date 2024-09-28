<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusKaryawan extends Model
{
    use HasFactory;

    protected $table = 'status_karyawans'; // Nama tabel
    protected $fillable = ['id','nama_status']; // Kolom yang bisa diisi secara massal
}
