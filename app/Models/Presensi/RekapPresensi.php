<?php

namespace App\Models\Presensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapPresensi extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'rekap_presensi';

    protected $guarded = [];
    public $timestamps = false;
}
