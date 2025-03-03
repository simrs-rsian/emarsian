<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingPotongan extends Model
{
    use HasFactory;

    protected $table = 'setting_potongans';
    protected $fillable = [
        'default_gaji_id', 'employee_id', 'nominal'
    ];

    public function default_gaji()
    {
        return $this->belongsTo(DefaultGaji::class);
    }
}
