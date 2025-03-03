<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingGaji extends Model
{
    use HasFactory;

    protected $table = 'setting_gajis';
    protected $fillable = [
        'default_gaji_id', 'employee_id', 'nominal'
    ];

    public function default_gaji()
    {
        return $this->belongsTo(DefaultGaji::class);
    }
}
