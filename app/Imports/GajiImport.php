<?php

namespace App\Imports;

use App\Models\Keuangan\SettingGaji;
use App\Models\Keuangan\DefaultGaji;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GajiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Lewati dua baris pertama (header dan baris kosong)
        static $rowNumber = 0;
        $rowNumber++;
        if ($rowNumber <= 1) {
            return null;
        }
        // Pastikan 'ID' ada dalam row
        if (!isset($row['id']) || empty($row['id'])) {
            return null; // Lewati jika tidak ada ID
        }

        // Loop melalui DefaultGaji untuk memetakan setiap kolom berdasarkan id dari DefaultGaji
        $columns = DefaultGaji::where('mode_id', 1)->get(); // Sesuaikan dengan mode_id yang relevan

        foreach ($columns as $col) {
            // Pastikan bahwa kolom ada dalam row (sesuaikan dengan nama kolom di Excel)
            if (!array_key_exists($col->id, $row)) {
                continue; // Lewati jika kolom tidak ditemukan dalam Excel
            }

            // Ambil nilai nominal dari Excel (default 0 jika kosong)
            $nominal = !empty($row[$col->id]) ? $row[$col->id] : 0;

            // dd($nominal);

            // Cari DefaultGaji berdasarkan id
            $defaultGaji = DefaultGaji::find($col->id);  // Menggunakan id untuk mencari DefaultGaji

            if (!$defaultGaji) {
                continue; // Lewati jika DefaultGaji tidak ditemukan
            }

            // ID Karyawan dari Excel (gunakan ID yang ada di kolom Excel)
            $employeeId = $row['id']; // ID karyawan dari Excel
            $employeeNIP = $row['nip']; // NIP karyawan dari Excel
            $employeeName = $row['nama']; // Nama karyawan dari Excel

            // dd($employeeId, $employeeNIP, $employeeName);

            // Update atau buat SettingGaji baru berdasarkan DefaultGaji id dan employee_id
            SettingGaji::updateOrCreate(
                [
                    'default_gaji_id' => $defaultGaji->id,  // ID DefaultGaji yang ditemukan
                    'employee_id' => $employeeId,           // ID karyawan dari Excel
                ],
                [
                    'nominal' => $nominal                    // Memperbarui nominal
                ]
            );
        }
    }
}
