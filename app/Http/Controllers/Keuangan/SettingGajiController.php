<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\Keuangan\SettingGaji;
use App\Models\Keuangan\DefaultGaji;
use App\Models\Keuangan\SettingPotongan;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\DB;

class SettingGajiController extends Controller
{
    public function index() {

        $employees = Employee::select('employees.*', 'status_karyawans.nama_status AS namastatuskar', 'status_keluargas.nama_status AS namastatuskel', 'pendidikans.nama_pendidikan', 'profesis.nama_profesi', 'units.nama_unit', 'golongans.nama_golongan', 'kelompok_umurs.nama_kelompok')
                        ->join('status_karyawans', 'employees.status_karyawan', 'status_karyawans.id')
                        ->join('status_keluargas', 'employees.status_keluarga', 'status_keluargas.id')
                        ->join('profesis', 'employees.profesi', 'profesis.id')
                        ->join('pendidikans', 'employees.pendidikan', 'pendidikans.id')
                        ->join('units', 'employees.jabatan_struktural', 'units.id')
                        ->join('golongans', 'employees.golongan', 'golongans.id')
                        ->join('kelompok_umurs', 'employees.kelompok_usia', 'kelompok_umurs.id')
                        ->get();
        return view('keuangan.set_gaji.index', compact('employees'));
    }

    public function show(Request $request, $id)
    {
        $employees = Employee::with('golongan', 'unit')->where('id', $id)->first();

        // Ambil semua default gaji & potongan
        $defaultgaji = DefaultGaji::where('mode_id', 1)->get();
        $defaultpotongan = DefaultGaji::where('mode_id', 2)->get();

        // Ambil data yang sudah ada di setting_gajis dan setting_potongans
        $settingGaji = SettingGaji::where('employee_id', $id)->get()->keyBy('default_gaji_id');
        $settingPotongan = SettingPotongan::where('employee_id', $id)->get()->keyBy('default_gaji_id');

        return view('keuangan.set_gaji.show', compact('employees', 'defaultgaji', 'defaultpotongan', 'settingGaji', 'settingPotongan'));
    }

    public function storeOrUpdate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // **Simpan atau update data Gaji**
            foreach ($request->gaji as $default_gaji_id => $nominal) {
                SettingGaji::updateOrCreate(
                    ['employee_id' => $id, 'default_gaji_id' => $default_gaji_id],
                    ['nominal' => $nominal]
                );
            }

            // **Simpan atau update data Potongan**
            foreach ($request->potongan as $default_gaji_id => $nominal) {
                SettingPotongan::updateOrCreate(
                    ['employee_id' => $id, 'default_gaji_id' => $default_gaji_id],
                    ['nominal' => $nominal]
                );
            }

            DB::commit();
            return redirect()->route('setting_gaji.show', $id)->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('setting_gaji.show', $id)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
