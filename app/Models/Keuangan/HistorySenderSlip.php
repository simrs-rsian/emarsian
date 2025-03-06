<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorySenderSlip extends Model
{
    use HasFactory;

    protected $table = 'history_sender_slips';

    protected $fillable = [
        'slip_penggajian_id',
        'user_id',
        'status',
        'message',
        'link',
        'status_downloader'
    ];
}
