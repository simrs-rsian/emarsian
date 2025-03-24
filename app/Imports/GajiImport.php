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
        ini_set('max_execution_time', 0); // Unlimited execution time
        ini_set('memory_limit', '-1'); // Unlimited memory
        // Lewati dua baris pertama (header dan baris kosong)
        static $rowNumber = 0;
        $rowNumber++;
        if ($rowNumber <= 1) {
            return null;
        }
        // Pastikan ID karyawan valid
        if (!isset($row['id']) || empty(trim($row['id']))) {
            return null; // Lewati jika tidak ada ID
        }

        // Ambil ID Karyawan, NIP, dan Nama dari Excel
        $employeeId = trim($row['id']);
        $employeeNIP = isset($row['nip']) ? trim($row['nip']) : null;
        $employeeName = isset($row['nama']) ? trim($row['nama']) : null;

        // Ambil semua DefaultGaji yang sesuai dengan mode_id = 1
        $columns = DefaultGaji::where('mode_id', 1)->get()->keyBy('id');

        // Loop untuk setiap DefaultGaji dan cocokkan dengan Excel
        foreach ($columns as $col) {
            // Pastikan bahwa kolom ada dalam row Excel
            if (!array_key_exists($col->id, $row)) {
                continue; // Lewati jika kolom tidak ditemukan dalam Excel
            }

            // Ambil nilai nominal dari Excel (gunakan default 0 jika kosong atau -)
            $nominal = (!empty($row[$col->id]) && $row[$col->id] !== '-') ? $row[$col->id] : 0;

            // Pastikan nominal adalah angka
            if (!is_numeric($nominal)) {
                $nominal = 0; // Jika bukan angka, set default ke 0
            }

            // Simpan atau perbarui data di SettingGaji
            SettingGaji::updateOrCreate(
                [
                    'default_gaji_id' => $col->id,
                    'employee_id' => $employeeId,
                ],
                [
                    'nominal' => $nominal
                ]
            );
        }
    }
}