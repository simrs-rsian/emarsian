<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee\Employee;
use App\Models\Master\Golongan;
use App\Models\Master\unit;
use App\Models\Master\Pendidikan;
use App\Models\Master\Profesi;
use App\Models\Master\StatusKeluarga;
use App\Models\Master\StatusKaryawan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeeImport; // Import class EmployeeImport
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::select('employees.*', 'status_karyawans.nama_status AS namastatuskar', 'status_keluargas.nama_status AS namastatuskel', 'pendidikans.nama_pendidikan', 'profesis.nama_profesi', 'units.nama_unit', 'golongans.nama_golongan', 'kelompok_umurs.nama_kelompok')
                        ->join('status_karyawans', 'employees.status_karyawan', 'status_karyawans.id')
                        ->join('status_keluargas', 'employees.status_keluarga', 'status_keluargas.id')
                        ->join('profesis', 'employees.profesi', 'profesis.id')
                        ->join('pendidikans', 'employees.pendidikan', 'pendidikans.id')
                        ->join('units', 'employees.jabatan_struktural', 'units.id')
                        ->join('golongans', 'employees.golongan', 'golongans.id')
                        ->join('kelompok_umurs', 'employees.kelompok_usia', 'kelompok_umurs.id')
                        ->get();        
        return view('employee.index', compact('employees'));
    }

    public function create()
    {
        // Ambil data untuk dropdown select
        $pendidikans = Pendidikan::all();
        $profesis = Profesi::all();
        $statuskaryawans = StatusKaryawan::all();
        $statuskeluargas = StatusKeluarga::all();
        $units = Unit::all();
        $golongans = Golongan::all();

        return view('employee.create', compact('pendidikans', 'profesis', 'statuskaryawans', 'statuskeluargas', 'units', 'golongans'));
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nik_karyawan' => 'required|unique:employees,nik_karyawan',
            'nip_karyawan' => 'required',
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat_lengkap' => 'required',
            'telepon' => 'required',
            'golongan_darah' => 'nullable',
            'tmt' => 'required|date',
            'tmta' => 'nullable|date',
            'pendidikan' => 'required|exists:pendidikans,id',
            'jurusan' => 'required',
            'profesi' => 'required|exists:profesis,id',
            'status_karyawan' => 'required|exists:status_karyawans,id',
            'status_keluarga' => 'required|exists:status_keluargas,id',
            'jabatan_struktural' => 'required|exists:units,id',
            'golongan' => 'required|exists:golongans,id',
            'alamat_lengkap' => 'required',
            'nomor_hp' => 'nullable|regex:/^08[0-9]{9,11}$/',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // validasi untuk file photo
            'bpjs_kesehatan' => 'nullable',
            'bpjs_ketenagakerjaan' => 'nullable',
            'npwp' => 'nullable',
        ], [
            'nip_karyawan.required' => 'NIP Karyawan harus diisi.',
            'nik_karyawan.unique' => 'NIK Karyawan sudah digunakan.',
            'nama_lengkap.required' => 'Nama Lengkap harus diisi.',
            'jenis_kelamin.required' => 'Jenis Kelamin harus dipilih.',
            'jenis_kelamin.in' => 'Jenis Kelamin tidak valid.',
            'golongan_darag' => 'Golongan darah tidak valid.',
            'tempat_lahir.required' => 'Tempat Lahir harus diisi.',
            'tanggal_lahir.required' => 'Tanggal Lahir harus diisi.',
            'tanggal_lahir.date' => 'Tanggal Lahir harus berupa tanggal yang valid.',
            'tmt.required' => 'Tanggal Mulai Tugas (TMT) harus diisi.',
            'tmt.date' => 'Tanggal Mulai Tugas (TMT) harus berupa tanggal yang valid.',
            'tmta.required' => 'Tanggal Masa Tugas Akhir (TMTA) harus diisi.',
            'tmta.date' => 'Tanggal Masa Tugas Akhir (TMTA) harus berupa tanggal yang valid.',
            'pendidikan.required' => 'Pendidikan harus diisi.',
            'jurusan.required' => 'Jurusan harus diisi.',
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
            'alamat_lengkap.required' => 'Alamat Lengkap harus diisi.',
            'nomor_hp.regex' => 'Nomor HP harus dimulai dengan 08 dan diikuti 9-11 angka.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'File gambar harus berformat jpeg, png, atau jpg.',
            'photo.max' => 'File gambar tidak boleh lebih dari 2MB.',
        ]);

        // Jika validasi gagal, kembali ke form dengan data yang terisi
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Menghitung umur dari tanggal lahir
        $tanggalLahir = Carbon::parse($request->tanggal_lahir);
        $umur = $tanggalLahir->age;

        // Menentukan kelompok usia
        $kelompokUsia = $this->getKelompokUsia($umur);

        // Menyimpan data karyawan
        $employeeData = $request->all();
        $employeeData['password'] = md5('123456'); // Password default MD5
        $employeeData['umur'] = $umur;
        $employeeData['kelompok_usia'] = $kelompokUsia;

        // Menyimpan photo jika diupload
        if ($request->hasFile('photo')) {
            // Menyimpan gambar ke folder public/images/karyawan
            $photoPath = $request->file('photo')->move(public_path('images/karyawan'), $request->file('photo')->getClientOriginalName());
            $employeeData['photo'] = 'images/karyawan/' . $request->file('photo')->getClientOriginalName();
        }

        // dd($employeeData);

        Employee::create($employeeData);
        return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil ditambahkan');
    }

    public function edit(Employee $employee)
    {
        $pendidikans = Pendidikan::all();
        $profesis = Profesi::all();
        $statuskaryawans = StatusKaryawan::all();
        $statuskeluargas = StatusKeluarga::all();
        $units = Unit::all();
        $golongans = Golongan::all();

        return view('employee.edit', compact('employee', 'pendidikans', 'profesis', 'statuskaryawans', 'statuskeluargas', 'units', 'golongans'));
    }

    public function update(Request $request, Employee $employee)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nik_karyawan' => 'required|unique:employees,nik_karyawan,' . $employee->id,
            'nip_karyawan' => 'required',
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat_lengkap' => 'required',
            'telepon' => 'required',
            'golongan_darah' => 'nullable',
            'tmt' => 'required|date',
            'tmta' => 'nullable|date',
            'pendidikan' => 'required|exists:pendidikans,id',
            'jurusan' => 'required',
            'profesi' => 'required|exists:profesis,id',
            'status_karyawan' => 'required|exists:status_karyawans,id',
            'status_keluarga' => 'required|exists:status_keluargas,id',
            'jabatan_struktural' => 'required|exists:units,id',
            'golongan' => 'required|exists:golongans,id',
            'nomor_hp' => 'nullable|regex:/^08[0-9]{9,11}$/',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bpjs_kesehatan' => 'nullable',
            'bpjs_ketenagakerjaan' => 'nullable',
            'npwp' => 'nullable',
        ], [
            // Custom error messages
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
            'tmta.required' => 'Tanggal Masa Tugas Akhir (TMTA) harus diisi.',
            'tmta.date' => 'Tanggal Masa Tugas Akhir (TMTA) harus berupa tanggal yang valid.',
            'pendidikan.required' => 'Pendidikan harus diisi.',
            'jurusan.required' => 'Pendidikan harus diisi.',
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
            'alamat_lengkap.required' => 'Alamat Lengkap harus diisi.',
            'nomor_hp.regex' => 'Nomor HP harus dimulai dengan 08 dan diikuti 9-11 angka.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'File gambar harus berformat jpeg, png, atau jpg.',
            'photo.max' => 'File gambar tidak boleh lebih dari 2MB.',
        ]);

        // Jika validasi gagal, kembali ke form dengan data yang terisi
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Menghitung umur dari tanggal lahir jika tanggal lahir berubah
        $tanggalLahir = Carbon::parse($request->tanggal_lahir);
        $umur = $tanggalLahir->age;
        $kelompokUsia = $this->getKelompokUsia($umur);

        // Menyiapkan data untuk pembaruan
        $employeeData = $request->except('photo'); // Menghindari penggantian kolom photo jika tidak ada file baru

        // Menghitung umur dan kelompok usia
        $employeeData['umur'] = $umur;
        $employeeData['kelompok_usia'] = $kelompokUsia;

        // Menyimpan photo baru jika diupload
        if ($request->hasFile('photo')) {
            // Hapus photo lama jika ada
            if ($employee->photo && file_exists(public_path($employee->photo))) {
                unlink(public_path($employee->photo));
            }

            // Menyimpan gambar ke folder public/images/karyawan
            $photoPath = $request->file('photo')->move(public_path('images/karyawan'), $request->file('photo')->getClientOriginalName());
            $employeeData['photo'] = 'images/karyawan/' . $request->file('photo')->getClientOriginalName();
        }

        // Memperbarui data karyawan
        $employee->update($employeeData);

        return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil diperbarui');
    }

    // Menentukan kelompok usia berdasarkan umur
    private function getKelompokUsia($umur)
    {
        if ($umur >= 18 && $umur <= 19) return '1';
        if ($umur >= 20 && $umur <= 22) return '2';
        if ($umur >= 23 && $umur <= 25) return '3';
        if ($umur >= 26 && $umur <= 30) return '4';
        if ($umur >= 31 && $umur <= 35) return '5';
        if ($umur >= 36 && $umur <= 40) return '6';
        if ($umur >= 41 && $umur <= 45) return '7';
        if ($umur >= 46 && $umur <= 50) return '8';
        if ($umur >= 51 && $umur <= 55) return '9';
        if ($umur >= 56 && $umur <= 60) return '10';
        if ($umur >= 61 && $umur <= 65) return '11';
        return '12';
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employee.index')->with('success', 'Employee deleted successfully');
    }
    
    public function show(Employee $employee)
    {
        // Ambil data employee beserta join tabel terkait
        $employee = Employee::select('employees.*', 
                                    'status_karyawans.nama_status AS namastatuskar', 
                                    'status_keluargas.nama_status AS namastatuskel', 
                                    'pendidikans.nama_pendidikan', 
                                    'profesis.nama_profesi', 
                                    'units.nama_unit', 
                                    'golongans.nama_golongan', 
                                    'kelompok_umurs.nama_kelompok')
                            ->join('status_karyawans', 'employees.status_karyawan', '=', 'status_karyawans.id')
                            ->join('status_keluargas', 'employees.status_keluarga', '=', 'status_keluargas.id')
                            ->join('profesis', 'employees.profesi', '=', 'profesis.id')
                            ->join('pendidikans', 'employees.pendidikan', '=', 'pendidikans.id')
                            ->join('units', 'employees.jabatan_struktural', '=', 'units.id')
                            ->join('golongans', 'employees.golongan', '=', 'golongans.id')
                            ->join('kelompok_umurs', 'employees.kelompok_usia', '=', 'kelompok_umurs.id')
                            ->where('employees.id', $employee->id)  // Filter by the employee ID
                            ->firstOrFail();  // Ambil satu data atau fail
        $pendidikan = DB::table('riwayat_pendidikans as p')
                            ->leftJoin('employees as e', 'p.id_employee', '=', 'e.id')
                            ->select('p.*')
                            ->where('e.id', $employee->id)
                            ->get();
        $pelatihan = DB::table('riwayat_pelatihans as p')
                            ->leftJoin('employees as e', 'p.id_employee', '=', 'e.id')
                            ->select('p.*')
                            ->where('e.id', $employee->id)
                            ->get();
        $jabatan = DB::table('riwayat_jabatans as j')
                            ->leftJoin('employees as e', 'j.id_employee', '=', 'e.id')
                            ->select('j.*')
                            ->where('e.id', $employee->id)
                            ->get();
        $keluarga = DB::table('riwayat_keluargas as k')
                            ->leftJoin('employees as e', 'k.id_employee', '=', 'e.id')
                            ->select('k.*')
                            ->where('e.id', $employee->id)
                            ->get();
                            
        return view('employee.show', compact('employee','pendidikan','pelatihan','jabatan','keluarga'));
    }

    public function updatePassword(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'passnew' => 'required|min:8',
            'passconf' => 'required|same:passnew',
        ], [
            'passnew.required' => 'Password baru wajib diisi.',
            'passnew.min' => 'Password baru minimal 8 karakter.',
            'passconf.required' => 'Konfirmasi password wajib diisi.',
            'passconf.same' => 'Konfirmasi password harus sama dengan password baru.',
        ]);

        // Cari pegawai berdasarkan ID
        $employee = Employee::findOrFail($id);

        // Update password pegawai menggunakan MD5
        $employee->password = md5($request->passnew);
        $employee->save();

        // Redirect atau berikan respon sesuai kebutuhan
        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }

    public function viewImport(){
        return view('employee.viewImport');
    }  

    public function import(Request $request)
    {
        // Validasi file input
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        // Proses import menggunakan Excel
        Excel::import(new EmployeeImport, $request->file('file'));

        return redirect()->route('employee.index')->with('success', 'Data karyawan berhasil diimport');
    }

    public function downloadImportTemplate()
    {
        // Nama file template yang disimpan di storage
        $filename = 'format_data_karyawan.xlsx';

        // Tentukan path file
        $filePath = storage_path("app/public/files/{$filename}");

        // Cek apakah file ada di server
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            // Jika file tidak ditemukan, tampilkan pesan error
            abort(404, 'File not found.');
        }
    }
}
