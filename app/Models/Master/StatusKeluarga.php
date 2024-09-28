<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusKeluarga extends Model
{
    use HasFactory;

    protected $table = 'status_keluargas'; // Nama tabel
    protected $fillable = ['id', 'nama_status']; // Kolom yang bisa diisi secara massal
}
