<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'units'; // Nama tabel
    protected $fillable = ['id','nama_unit']; // Kolom yang bisa diisi secara massal
}
