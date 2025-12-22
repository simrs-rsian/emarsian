<?php

namespace App\Models\Perizinan\Cuti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCuti extends Model
{
    use HasFactory;
    protected $table = 'data_cutis';
    protected $primaryKey = 'kode_cuti';
    public $incrementing = false; // karena primary key adalah string
    protected $keyType = 'string';

    protected $fillable = [
        'kode_cuti',
        'id_employee',
        'id_jenis_cuti',
        'id_employee_cuti',
        'tanggal_mulai_cuti',
        'tanggal_selesai_cuti',
        'jumlah_hari_cuti',
        'alasan_cuti',
        'karyawan_pengganti',
        'ttd_karyawan_pemohon',
        'ttd_karyawan_pengganti',
        'menyetujui',
        'ttd_menyetujui',
        'mengetahui',
        'ttd_mengetahui',
        'pencatat',
        'ttd_pencatat',
    ];
    public function dataCutis()
    {
        return $this->hasMany(DataCuti::class, 'id_jenis_cuti', 'id');
    }
}
