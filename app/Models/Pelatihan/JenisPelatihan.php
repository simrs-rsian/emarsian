<?php

namespace App\Models\Pelatihan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPelatihan extends Model
{
    use HasFactory;

    protected $table = 'jenis_pelatihans'; // Nama tabel
    protected $fillable = ['nama_jenis','id']; // Kolom yang bisa diisi secara massal
}
