<?php

namespace App\Models\Riwayat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKontrak extends Model
{
    protected $fillable = ['id_employee', 'tanggal_mulai', 'tanggal_selesai', 'dokumen'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee\Employee', 'employees', 'id');
    }
}
