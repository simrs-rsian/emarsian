<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianSlipPotongan extends Model
{
    use HasFactory;

    protected $table = 'rincian_slip_potongans';
    protected $fillable = [
        'nama_potongan', 'nominal_potongan', 'slip_penggajian_id'
    ];

    public function slip_penggajian()
    {
        return $this->belongsTo(SlipPenggajian::class);
    }
}
