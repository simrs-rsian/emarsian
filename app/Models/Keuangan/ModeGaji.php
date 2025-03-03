<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeGaji extends Model
{
    use HasFactory;

    protected $table = 'mode_gajis';
    protected $fillable = ['mode_nama'];
}
