<?php

namespace App\Models\Perizinan\Cuti;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee\Employee;

class EmployeeCuti extends Model
{
    use HasFactory;
    protected $table = 'employee_cutis';

    protected $fillable = [
        'employee_id',
        'tahun',
        'periode',
        'jumlah_cuti',
        'cuti_diambil',
        'sisa_cuti',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

}
