<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'nama_role'];

    public function hakAkses()
    {
        return $this->hasMany(HakAkses::class, 'role_id', 'id');
    }

    public function navmenus()
    {
        return $this->hasMany(Navmenus::class, 'role_id');
    }
}
