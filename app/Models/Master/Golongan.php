<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    use HasFactory;

    protected $table = 'golongans'; // Nama tabel
    protected $fillable = ['nama_golongan','id']; // Kolom yang bisa diisi secara massal
}