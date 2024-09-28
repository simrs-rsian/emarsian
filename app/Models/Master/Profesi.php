<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesi extends Model
{
    use HasFactory;

    protected $table = 'profesis'; // Nama tabel
    protected $fillable = ['id','nama_profesi']; // Kolom yang bisa diisi secara massal
}
