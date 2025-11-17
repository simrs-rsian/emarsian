<?php

namespace App\Models\Riwayat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPendidikan extends Model
{
    protected $fillable = ['id_employee', 'tahun_masuk', 'tahun_lulus', 'nama_sekolah', 'lokasi', 'dokumen', 'jenis_data'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee\Employee', 'employees', 'id');
    }
}
