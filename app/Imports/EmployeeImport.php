<?php

namespace App\Imports;

use App\Models\Employee\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EmployeeImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * Model the row
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        Log::info('Processing row:', $row);

        // Menghitung umur dari tanggal lahir jika valid
        if (!empty($row['tanggal_lahir'])) {
            try {
                $tanggalLahir = Carbon::parse($row['tanggal_lahir']);
                $umur = $tanggalLahir->age;
            } catch (\Exception $e) {
                Log::error("Error parsing tanggal_lahir for row: " . json_encode($row));
                $umur = null;  // Jika ada masalah parsing tanggal, set umur ke null
            }
        } else {
            $umur = null;
        }

        // Menentukan kelompok usia
        $kelompokUsia = $this->getKelompokUsia($umur);

        // Menyusun data karyawan
        $employeeData = [
            'nik_karyawan' => $row['nik_karyawan'],
            'nip_karyawan' => $row['nip_karyawan'],
            'nama_lengkap' => $row['nama_lengkap'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
            'alamat_lengkap' => $row['alamat_lengkap'],
            'telepon' => $row['telepon'] ?? null,
            'golongan_darah' => $row['golongan_darah'] ?? null,
            'tmt' => $row['tmt'] ?? null,
            'tmta' => $row['tmta'] ?? null,
            'pendidikan' => $row['pendidikan'],
            'profesi' => $row['profesi'],
            'status_karyawan' => $row['status_karyawan'],
            'status_keluarga' => $row['status_keluarga'],
            'jabatan_struktural' => $row['jabatan_struktural'],
            'golongan' => $row['golongan'],
            'bpjs_kesehatan' => $row['bpjs_kesehatan'] ?? null,
            'bpjs_ketenagakerjaan' => $row['bpjs_ketenagakerjaan'] ?? null,
            'npwp' => $row['npwp'] ?? null,
            'password' => md5('123456'),  // Password default MD5
            'umur' => $umur,
            'kelompok_usia' => $kelompokUsia,
        ];

        // Validasi data input
        $validator = Validator::make($employeeData, $this->rules(), $this->messages());

        // Jika validasi gagal, log error dan abaikan baris ini, tapi lanjutkan import
        if ($validator->fails()) {
            Log::error('Validation failed for row: ' . json_encode($row) . ' - Errors: ' . json_encode($validator->errors()));
            return null;
        }

        // Menyimpan data karyawan
        return new Employee($employeeData);
    }

    /**
     * Menentukan kelompok usia berdasarkan umur
     *
     * @param int $umur
     * @return string
     */
    private function getKelompokUsia($umur)
    {
        if ($umur >= 18 && $umur <= 19) return '18-19 tahun';
        if ($umur >= 20 && $umur <= 22) return '20-22 tahun';
        if ($umur >= 23 && $umur <= 25) return '23-25 tahun';
        if ($umur >= 26 && $umur <= 30) return '26-30 tahun';
        if ($umur >= 31 && $umur <= 35) return '31-35 tahun';
        if ($umur >= 36 && $umur <= 40) return '36-40 tahun';
        if ($umur >= 41 && $umur <= 45) return '41-45 tahun';
        if ($umur >= 46 && $umur <= 50) return '46-50 tahun';
        if ($umur >= 51 && $umur <= 55) return '51-55 tahun';
        if ($umur >= 56 && $umur <= 60) return '56-60 tahun';
        if ($umur >= 61 && $umur <= 65) return '61-65 tahun';
        return 'Tidak Diketahui';
    }

    /**
     * Validasi data untuk setiap baris
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nik_karyawan' => 'required|unique:employees,nik_karyawan',
            'nip_karyawan' => 'required',
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat_lengkap' => 'required',
            'telepon' => 'nullable|regex:/^08[0-9]{9,11}$/',
            'golongan_darah' => 'nullable',
            'tmt' => 'required|date',
            'tmta' => 'nullable|date',
            'pendidikan' => 'required|exists:pendidikans,id',
            'profesi' => 'required|exists:profesis,id',
            'status_karyawan' => 'required|exists:status_karyawans,id',
            'status_keluarga' => 'required|exists:status_keluargas,id',
            'jabatan_struktural' => 'required|exists:units,id',
            'golongan' => 'required|exists:golongans,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bpjs_kesehatan' => 'nullable',
            'bpjs_ketenagakerjaan' => 'nullable',
            'npwp' => 'nullable',
        ];
    }

    /**
     * Custom error messages for validation
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nik_karyawan.required' => 'NIK Karyawan harus diisi.',
            'nik_karyawan.unique' => 'NIK Karyawan sudah digunakan.',
            'nip_karyawan.required' => 'NIP Karyawan harus diisi.',
            'nama_lengkap.required' => 'Nama Lengkap harus diisi.',
            'jenis_kelamin.required' => 'Jenis Kelamin harus dipilih.',
            'jenis_kelamin.in' => 'Jenis Kelamin tidak valid.',
            'tempat_lahir.required' => 'Tempat Lahir harus diisi.',
            'tanggal_lahir.required' => 'Tanggal Lahir harus diisi.',
            'tanggal_lahir.date' => 'Tanggal Lahir harus berupa tanggal yang valid.',
            'tmt.required' => 'Tanggal Mulai Tugas (TMT) harus diisi.',
            'tmt.date' => 'Tanggal Mulai Tugas (TMT) harus berupa tanggal yang valid.',
            'tmta.date' => 'Tanggal Masa Tugas Akhir (TMTA) harus berupa tanggal yang valid.',
            'pendidikan.required' => 'Pendidikan harus diisi.',
            'pendidikan.exists' => 'Pendidikan tidak ditemukan.',
            'profesi.required' => 'Profesi harus diisi.',
            'profesi.exists' => 'Profesi tidak ditemukan.',
            'status_karyawan.required' => 'Status Karyawan harus diisi.',
            'status_karyawan.exists' => 'Status Karyawan tidak ditemukan.',
            'status_keluarga.required' => 'Status Keluarga harus diisi.',
            'status_keluarga.exists' => 'Status Keluarga tidak ditemukan.',
            'jabatan_struktural.required' => 'Jabatan Struktural harus diisi.',
            'jabatan_struktural.exists' => 'Jabatan Struktural tidak ditemukan.',
            'golongan.required' => 'Golongan harus diisi.',
            'golongan.exists' => 'Golongan tidak ditemukan.',
            'photo.image' => 'Foto harus berupa gambar dengan format jpeg, png, atau jpg.',
            'photo.mimes' => 'Foto hanya boleh berupa file dengan format jpeg, png, atau jpg.',
            'photo.max' => 'Ukuran file foto tidak boleh lebih dari 2MB.',
        ];
    }
}
