<?php

namespace App\Models\Riwayat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatJabatan extends Model
{
    protected $fillable = ['id_employee', 'tahun_mulai', 'tahun_selesai', 'keterangan'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee\Employee', 'employees', 'id');
    }
}
