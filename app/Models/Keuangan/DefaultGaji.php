<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultGaji extends Model
{
    use HasFactory;

    protected $table = 'default_gajis';

    protected $fillable = [
        'gaji_nama', 'mode_id'
    ];

    public function mode()
    {
        return $this->belongsTo(ModeGaji::class, 'mode_id');
    }
}
