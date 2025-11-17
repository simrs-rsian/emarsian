<?php

namespace App\Models\Riwayat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKeluarga extends Model
{
    protected $fillable = ['id_employee', 'nama_keluarga', 'status_keluarga', 'pekerjaan_keluarga', 'pendidikan_keluarga', 'dokumen', 'jenis_data'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee\Employee', 'employees', 'id');
    }
}
