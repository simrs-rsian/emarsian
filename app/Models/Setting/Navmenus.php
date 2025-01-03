<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navmenus extends Model
{
    use HasFactory;

    protected $table = 'navmenus';
    protected $primaryKey = 'm_id';

    protected $fillable = [
        'm_name',
        'm_link',
        'm_link_child',
        'm_icon',
        'm_child',
        'm_order',
        'm_status',
    ];

    public function hakAkses()
    {
        return $this->hasMany(HakAkses::class, 'ha_menu_id', 'm_id');
    }
}
