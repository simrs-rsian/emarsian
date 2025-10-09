<?php

namespace App\Models\Presensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryPresensi extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'temporary_presensi';
    protected $guarded = [];
    public $timestamps = false;
}
