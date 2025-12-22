<?php

namespace App\Exports;

use App\Models\Employee\Employee;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeCutiExport implements WithHeadings, FromArray
{
    public function headings(): array
    {
        return [
            ['ID', 'NIP', 'Nama', 'Tahun', 'Periode', 'Jumlah_Cuti', 'Cuti_Diambil'],
            ['','', '', '(Wajib Diisi, Misal : 2023)', '(Periode 1 = Januari - Juni, Periode 2 = Juli - Desember. Wajib Diisi, Misal : 1)', '(Wajib Diisi, Misal : 20)', '(Wajib Diisi, Misal : 20)'],
        ];
    }

    public function array(): array
    {
        $employees = Employee::leftJoin('employee_cutis', 'employee_cutis.employee_id', '=', 'employees.id')
            ->select(
                'employees.id as employee_id',
                'employees.nip_karyawan as employee_nip',
                'employees.nama_lengkap as employee_name',
                'employee_cutis.tahun',
                'employee_cutis.periode',
                'employee_cutis.jumlah_cuti',
                'employee_cutis.cuti_diambil'
            )
            ->where('employees.deleted_at', null)
            ->get();

        $data = [];
        foreach ($employees as $employee) {
            $data[] = [
                $employee->employee_id,
                $employee->employee_nip,
                $employee->employee_name,
                $employee->tahun,
                $employee->periode,
                $employee->jumlah_cuti,
                $employee->cuti_diambil,
            ];
        }

        return $data;
    }
}
