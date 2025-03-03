<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlipPenggajian extends Model
{
    use HasFactory;

    protected $table = 'slip_penggajians';
    protected $fillable = [
        'bulan', 'tahun', 'total_gaji', 'total_potongan', 'total_terima', 'employee_id'
    ];
}
