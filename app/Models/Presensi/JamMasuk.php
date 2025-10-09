<?php

namespace App\Models\Presensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamMasuk extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'jam_masuk';
    protected $guarded = [];
    
    public $timestamps = false;
}
