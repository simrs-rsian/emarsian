<?php

namespace App\Models\Riwayat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatSipp extends Model
{
    protected $fillable = ['id_employee', 'tanggal_berlaku', 'no_sipp', 'dokumen','no_str'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee\Employee', 'employees', 'id');
    }
}
