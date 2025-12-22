<?php

namespace App\Http\Controllers\Perizinan\Cuti;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perizinan\Cuti\EmployeeCuti;
use App\Models\Perizinan\Cuti\DataCuti;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\DB;
use App\Exports\EmployeeCutiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SettingCutiImport;
use Illuminate\Support\Carbon;

class DataCutiController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', now()->year);
        $periode = $request->get('periode', now()->month <= 6 ? 1 : 2);

        $cuti_datas = Employee::leftJoin('employee_cutis', 'employee_cutis.employee_id', '=', 'employees.id')
                        ->select(
                            'employee_cutis.*',
                            'employees.nama_lengkap as employee_name',
                            'employees.nip_karyawan as employee_nip'
                        )
                        ->whereNull('employees.deleted_at')
                        ->where('employee_cutis.tahun', $tahun)
                        ->where('employee_cutis.periode', $periode)
                        ->get();
        
        $employees = Employee::whereNull('deleted_at')->get();

        return view('perizinan.cuti.index', compact('cuti_datas', 'employees', 'tahun', 'periode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'  => 'required',
            'tahun'        => 'required',
            'periode'      => 'required',
            'jumlah_cuti'  => 'required|numeric|min:0',
            'cuti_diambil' => 'nullable|numeric|min:0',
        ]);

        // Cek duplikasi (employee_id + tahun + periode)
        $exists = EmployeeCuti::where('employee_id', $request->employee_id)
            ->where('tahun', $request->tahun)
            ->where('periode', $request->periode)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Data Cuti untuk karyawan tersebut pada tahun dan periode yang dipilih sudah ada.');
        }

        $cuti_diambil = $request->cuti_diambil ?? 0;
        $sisa_cuti    = $request->jumlah_cuti - $cuti_diambil;

        EmployeeCuti::create([
            'employee_id'  => $request->employee_id,
            'tahun'        => $request->tahun,
            'periode'      => $request->periode,
            'jumlah_cuti'  => $request->jumlah_cuti,
            'cuti_diambil' => $cuti_diambil,
            'sisa_cuti'    => $sisa_cuti,
        ]);

        return back()->with('success', 'Data Berhasil Ditambahkan.');
    }

    public function edit(Request $request, $pegawai)
    {
        // Ambil query string
        $tahun   = $request->tahun;
        $periode = $request->periode;

        // Ambil data cuti berdasarkan employee + tahun + periode
        $cuti = EmployeeCuti::leftJoin('employees', 'employee_cutis.employee_id', '=', 'employees.id')
            ->select(
                'employee_cutis.*',
                'employees.nama_lengkap as employee_name',
                'employees.nip_karyawan as employee_nip'
            )
            ->where('employee_id', $pegawai)
            ->where('tahun', $tahun)
            ->where('periode', $periode)
            ->firstOrFail();

        return view('perizinan.cuti.edit', compact(
            'cuti',
            'tahun',
            'periode'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $pegawai)
    {
        $request->validate([
            'jumlah_cuti'  => 'required|numeric|min:0',
            'cuti_diambil' => 'nullable|numeric|min:0',
            'tahun'        => 'required',
            'periode'      => 'required',
        ]);

        $cuti_diambil = $request->cuti_diambil ?? 0;
        $sisa_cuti    = $request->jumlah_cuti - $cuti_diambil;

        EmployeeCuti::where('employee_id', $pegawai)
            ->where('tahun', $request->tahun)
            ->where('periode', $request->periode)
            ->update([
                'jumlah_cuti'  => $request->jumlah_cuti,
                'cuti_diambil' => $cuti_diambil,
                'sisa_cuti'    => $sisa_cuti,
                'updated_at'   => now(),
            ]);

        return redirect()->route('perizinan.cuti.pegawai.index')
            ->with('success', 'Data Cuti berhasil diperbarui.');
    }

    public function exportEmployeeCuti()
    {
        // dd('masuk');
        return Excel::download(new EmployeeCutiExport, 'format_Setting_Cuti_Karyawan.xlsx');
    }

    public function importEmployeeCuti(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        // dd($request->file('file'));
        Excel::import(new SettingCutiImport, $request->file('file'));

        return back()->with('success', 'Data Import Setting Cuti berhasil diimport.');
    }
}
