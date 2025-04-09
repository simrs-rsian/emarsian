<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    use HasFactory;

    protected $table = 'web_settings';

    protected $fillable = [
        'id',
        'name',
        'title',
        'logo',
        'email',
        'phone',
        'address',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'website',
        'coursellink1',
        'coursellink2',
        'coursellink3',
    ];
}
