<?php

namespace App\Models\Perizinan\Cuti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCuti extends Model
{
    use HasFactory;
    protected $table = 'jenis_cutis';
    
    protected $fillable = [
        'nama_jenis_cuti',
    ];
}
