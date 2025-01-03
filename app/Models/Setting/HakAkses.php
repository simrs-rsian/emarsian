<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HakAkses extends Model
{
    use HasFactory;

    protected $table = 'hak_akses';
    protected $primaryKey = 'id';

    protected $fillable = [
        'role_id',
        'navmenu_id',
    ];

    public function navmenu()
    {
        return $this->belongsTo(Navmenus::class, 'navmenu_id', 'm_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
