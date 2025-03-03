<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianSlipGaji extends Model
{
    use HasFactory;

    protected $table = 'rincian_slip_gajis';
    protected $fillable = [
        'nama_gaji', 'nominal_gaji', 'slip_penggajian_id'
    ];
}
