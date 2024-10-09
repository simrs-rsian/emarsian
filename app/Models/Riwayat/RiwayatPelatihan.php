<?php

namespace App\Models\Riwayat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPelatihan extends Model
{
    protected $fillable = ['id_employee', 'nama_pelatihan', 'mulai', 'selesai', 'penyelenggara', 'lokasi', 'dokumen'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee\Employee', 'employees', 'id');
    }
}
