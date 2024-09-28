<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    use HasFactory;

    protected $table = 'pendidikans'; // Nama tabel
    protected $fillable = ['id','nama_pendidikan']; // Kolom yang bisa diisi secara massal
}
