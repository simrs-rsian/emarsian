<?php

namespace App\Imports;

use App\Models\Perizinan\Cuti\EmployeeCuti;
use App\Models\Employee\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SettingCutiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Validasi kolom wajib
        if (
            empty($row['id']) ||
            empty($row['tahun']) ||
            empty($row['periode']) ||
            empty($row['jumlah_cuti'])
        ) {
            return null;
        }

        $employeeId = trim($row['id']);

        // Cari employee
        $employee = Employee::where('id', $employeeId)->first();
        if (!$employee) {
            return null;
        }

        $tahun   = trim($row['tahun']);
        $periode = trim($row['periode']);

        // 🔒 Cek duplikasi (employee_id + tahun + periode)
        $exists = EmployeeCuti::where('employee_id', $employee->id)
            ->where('tahun', $tahun)
            ->where('periode', $periode)
            ->exists();

        // Jika sudah ada → abaikan
        if ($exists) {
            return null;
        }

        $jumlahCuti  = (int) $row['jumlah_cuti'];
        $cutiDiambil = isset($row['cuti_diambil']) && $row['cuti_diambil'] !== ''
            ? (int) $row['cuti_diambil']
            : 0;

        $sisaCuti = $jumlahCuti - $cutiDiambil;

        return new EmployeeCuti([
            'employee_id'  => $employee->id,
            'tahun'        => $tahun,
            'periode'      => $periode,
            'jumlah_cuti'  => $jumlahCuti,
            'cuti_diambil' => $cutiDiambil,
            'sisa_cuti'    => $sisaCuti,
        ]);
    }
}
