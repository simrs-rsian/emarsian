<?php

namespace App\Models\Pelatihan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    protected $fillable = ['nama_pelatihan', 'tanggal_mulai', 'tanggal_selesai', 'poin', 'penyelenggara', 'lokasi', 'jenis_pelatihan_id'];

    public function jenis_pelatihan()
    {
        return $this->belongsTo('App\Models\Pelatihan\JenisPelatihan', 'jenis_pelatihans', 'id');
    }
}
