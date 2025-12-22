<?php

namespace App\Http\Controllers\Perizinan\Cuti;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perizinan\Cuti\EmployeeCuti;
use App\Models\Perizinan\Cuti\DataCuti;
use App\Models\Perizinan\Cuti\JenisCuti;
use App\Models\Employee\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;
use Dompdf\Options;

class RiwayatCutiController extends Controller
{
    public function index(Request $request)
    {
        // dd('masuk ges');
        $id_employee = $request->query('employee_id');
        $employee = Employee::whereNull('deleted_at')
            ->where('id', $id_employee)
            ->firstOrFail();

        $tahun = now()->year;
        $currentMonth = now()->month;
        $periode = $currentMonth <= 6 ? 1 : 2;

        $employeeCuti = EmployeeCuti::where('employee_id', $id_employee)
            ->where('tahun', $tahun)
            ->where('periode', $periode)
            ->first();

        $sisaCuti = $employeeCuti
            ? ($employeeCuti->jumlah_cuti - $employeeCuti->cuti_diambil)
            : 0;

        // dd($employeeCuti);

        $dataCuti = DataCuti::where('id_employee_cuti', $employeeCuti->id)
            ->get();

        // ===============================
        // GENERATE KODE CUTI OTOMATIS
        // Format: YYYYMMDD-001
        // Reset tiap tahun
        // ===============================

        $today = Carbon::now()->format('Ymd'); // 20251216
        $year  = Carbon::now()->format('Y');   // 2025

        $lastKode = DataCuti::whereYear('created_at', $year)
            ->where('kode_cuti', 'like', $today . '-%')
            ->orderBy('kode_cuti', 'desc')
            ->value('kode_cuti');

        if ($lastKode) {
            $lastNumber = (int) substr($lastKode, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $kodeCuti = $today . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        //tampilkan jenis cuti
        $jenisCuti = JenisCuti::get();

        $riwayatCuti = DataCuti::leftJoin('jenis_cutis', 'data_cutis.id_jenis_cuti', '=', 'jenis_cutis.id')
            ->leftJoin('employee_cutis', 'data_cutis.id_employee_cuti', '=', 'employee_cutis.id')
            ->leftJoin('employees', 'employee_cutis.employee_id', '=', 'employees.id')
            ->select('data_cutis.*', 'employees.nama_lengkap as employee_name', 'employees.nip_karyawan as employee_nip', 'jenis_cutis.nama_jenis_cuti', 'employee_cutis.tahun', 'employee_cutis.periode')
            ->where('data_cutis.id_employee_cuti', $employeeCuti->id)
            ->get();

        $tanggal_cuti = DB::table('tanggal_cutis')->orderBy('tanggal_cuti', 'asc')->get();

        return view('perizinan.cuti.riwayat.index', compact(
            'employee',
            'employeeCuti',
            'dataCuti',
            'kodeCuti',
            'jenisCuti',
            'riwayatCuti',
            'sisaCuti',
            'tanggal_cuti'
        ));
    }

    public function store(Request $request) {
        // dd($request->all());
        //cek kebenaran data cuti
        $employeeCuti = EmployeeCuti::where('id', $request->id_employee_cuti)->first();
        if ($request->id_jenis_cuti == 1) {
            if ($request->jumlah_hari_cuti > $employeeCuti->sisa_cuti) {
                return back()->with('error', 'Jumlah hari cuti melebihi sisa cuti');
            }
        }
        $dataCuti = DataCuti::create($request->all());

        //update data cuti tahunan jika id_jenisnya 1
        if ($request->id_jenis_cuti == 1) {
            $jumlah_hari_cuti = $request->jumlah_hari_cuti;
            $employeeCuti = EmployeeCuti::where('id', $request->id_employee_cuti)->first();
            $employeeCuti->update([
                'cuti_diambil' => $employeeCuti->cuti_diambil + $jumlah_hari_cuti,
                'sisa_cuti' => $employeeCuti->jumlah_cuti - ($employeeCuti->cuti_diambil + $jumlah_hari_cuti)
            ]);
        }
        
            
        // input data tanggal cuti tahunan ke tabel tanggal_cutis sebanyak array
        // untuk id jenis cuti 1, 2, 4
        if (in_array($request->id_jenis_cuti, [1, 2, 4])) {
            foreach ($request->tanggal_cuti as $tanggal) {
                DB::table('tanggal_cutis')->insert([
                    'kode_cuti'   => $request->kode_cuti,
                    'tanggal_cuti' => $tanggal,
                ]);
            }
        }

        return back()->with('success', 'Cuti Berhasil ditambahkan silahkan validasi tanda tangan');;
    }

    public function edit($id) {
        $cuti = DataCuti::join('jenis_cutis', 'data_cutis.id_jenis_cuti', '=', 'jenis_cutis.id')
                ->select('data_cutis.*', 'jenis_cutis.nama_jenis_cuti')
                ->where('kode_cuti', $id)->first();
                // dd($cuti);
        //tampilkan jenis cuti
        $jenisCuti = JenisCuti::get();

        $tanggalCuti = DB::table('tanggal_cutis')
            ->where('kode_cuti', $id)
            ->pluck('tanggal_cuti')
            ->toArray();

        return view('perizinan.cuti.riwayat.edit', compact('cuti', 'jenisCuti', 'tanggalCuti'));
    }

    public function update(Request $request, $id) {
        dd($request->all());
        
        $dataCuti = DataCuti::where('kode_cuti', $id)->first();
        $dataCuti->update($request->all());

        //update data cuti tahunan jika id_jenisnya 1
        if (in_array($request->id_jenis_cuti, [1, 2, 4])) {
            // dd($request->all());
            //buat hapus data lama
            DB::table('tanggal_cutis')->where('kode_cuti', $id)->delete();

            //input data baru
            foreach ($request->tanggal_cuti as $tanggal) {
                DB::table('tanggal_cutis')->insert([
                    'kode_cuti'   => $request->kode_cuti,
                    'tanggal_cuti' => $tanggal,
                ]);
            }
        }

        return redirect()
        ->route('perizinan.riwayat.cuti.index', [
            'employee_id' => $request->id_employee
        ])
        ->with('success', 'Cuti Berhasil diubah');
    }

    public function destroy($id) {
        // dd($id);
        $dataCuti = DataCuti::where('kode_cuti', $id)->first();

        $employeeCuti = EmployeeCuti::where('id', $dataCuti->id_employee_cuti)->first();
        $employeeCuti->update([
            'cuti_diambil' => $employeeCuti->cuti_diambil - $dataCuti->jumlah_hari_cuti,
            'sisa_cuti' => $employeeCuti->sisa_cuti + $dataCuti->jumlah_hari_cuti
        ]);

        $dataCuti->delete();
        DB::table('tanggal_cutis')->where('kode_cuti', $id)->delete();

        return back()->with('success', 'Cuti Berhasil dihapus');
    }

    public function show($id)
    {
        $cuti = DataCuti::join('jenis_cutis', 'data_cutis.id_jenis_cuti', '=', 'jenis_cutis.id')
            ->join('employee_cutis', 'data_cutis.id_employee_cuti', '=', 'employee_cutis.id')
            ->join('employees', 'employee_cutis.employee_id', '=', 'employees.id')
            ->join('profesis', 'employees.profesi', '=', 'profesis.id')
            ->join('units', 'employees.jabatan_struktural', '=', 'units.id')
            ->select(
                'data_cutis.*',
                'employee_cutis.*',
                'employees.nama_lengkap',
                'employees.nip_karyawan',
                'employees.alamat_lengkap',
                'employee_cutis.tahun',
                'employee_cutis.periode',
                'jenis_cutis.nama_jenis_cuti',
                'profesis.nama_profesi',
                'units.nama_unit'
            )
            ->where('data_cutis.kode_cuti', $id)
            ->firstOrFail();

        // Ambil daftar tanggal cuti (jika ada)
        $tanggal_cuti = DB::table('tanggal_cutis')
            ->where('kode_cuti', $id)
            ->orderBy('tanggal_cuti', 'asc')
            ->pluck('tanggal_cuti')
            ->toArray();

        return view('perizinan.cuti.riwayat.show', compact('cuti', 'tanggal_cuti'));
    }

    public function ttdMengetahui(Request $request){
        // dd($request->all());
        $data = $request->input('picture');
        $kode_cuti = $request->input('kode_cuti');
        $mengetahui = $request->input('mengetahui');

        if (strpos($data, 'data:image/png;base64,') !== 0) {
            return response()->json(['success' => false, 'message' => 'Format gambar tidak valid.'], 400);
        }

        $data = str_replace('data:image/png;base64,', '', $data);

        if (!base64_decode($data, true)) {
            return response()->json(['success' => false, 'message' => 'Data base64 tidak valid.'], 400);
        }

        $fileName = 'mengetahui-' . str_replace('/', '_', $kode_cuti) .'-' . time() . '.png';  
        $filePath = public_path('assets/signature/cuti/mengetahui/') . $fileName;
        
        if (!file_exists(public_path('assets/signature/cuti/mengetahui/'))) {
            if (!mkdir(public_path('assets/signature/cuti/mengetahui/'), 0777, true)) {
                return response()->json(['success' => false, 'message' => 'Gagal membuat direktori untuk menyimpan tanda tangan.'], 500);
            }
        }
        
        // Decode data base64 dan simpan ke file
        if (file_put_contents($filePath, base64_decode($data)) === false) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan file tanda tangan.'], 500);
        }
        
        // Simpan data ke database
        $inserted = DataCuti::where('kode_cuti', $kode_cuti)
            ->update([
                'mengetahui' => $mengetahui,
                'ttd_mengetahui' => $fileName,
            ]);

        if (!$inserted) {
            // Jika gagal menyimpan ke database
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data ke database.'], 500);
        }
        
        // Jika semua berhasil
        return response()->json(['success' => true, 'file' => $fileName]);
        
    }

    public function ttdMenyetujui(Request $request){
        // dd($request->all());
        $data = $request->input('picture');
        $kode_cuti = $request->input('kode_cuti');
        $menyetujui = $request->input('menyetujui');

        if (strpos($data, 'data:image/png;base64,') !== 0) {
            return response()->json(['success' => false, 'message' => 'Format gambar tidak valid.'], 400);
        }

        $data = str_replace('data:image/png;base64,', '', $data);

        if (!base64_decode($data, true)) {
            return response()->json(['success' => false, 'message' => 'Data base64 tidak valid.'], 400);
        }

        $fileName = 'menyetujui-' . str_replace('/', '_', $kode_cuti) .'-' . time() . '.png';  
        $filePath = public_path('assets/signature/cuti/menyetujui/') . $fileName;
        
        if (!file_exists(public_path('assets/signature/cuti/menyetujui/'))) {
            if (!mkdir(public_path('assets/signature/cuti/menyetujui/'), 0777, true)) {
                return response()->json(['success' => false, 'message' => 'Gagal membuat direktori untuk menyimpan tanda tangan.'], 500);
            }
        }
        
        // Decode data base64 dan simpan ke file
        if (file_put_contents($filePath, base64_decode($data)) === false) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan file tanda tangan.'], 500);
        }
        
        // Simpan data ke database
        $inserted = DataCuti::where('kode_cuti', $kode_cuti)
            ->update([
                'menyetujui' => $menyetujui,
                'ttd_menyetujui' => $fileName,
            ]);

        if (!$inserted) {
            // Jika gagal menyimpan ke database
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data ke database.'], 500);
        }
        
        // Jika semua berhasil
        return response()->json(['success' => true, 'file' => $fileName]);
        
    }

    public function ttdPencatat(Request $request){
        // dd($request->all());
        $data = $request->input('picture');
        $kode_cuti = $request->input('kode_cuti');
        $pencatat = $request->input('pencatat');

        if (strpos($data, 'data:image/png;base64,') !== 0) {
            return response()->json(['success' => false, 'message' => 'Format gambar tidak valid.'], 400);
        }

        $data = str_replace('data:image/png;base64,', '', $data);

        if (!base64_decode($data, true)) {
            return response()->json(['success' => false, 'message' => 'Data base64 tidak valid.'], 400);
        }

        $fileName = 'pencatat-' . str_replace('/', '_', $kode_cuti) .'-' . time() . '.png';  
        $filePath = public_path('assets/signature/cuti/pencatat/') . $fileName;
        
        if (!file_exists(public_path('assets/signature/cuti/pencatat/'))) {
            if (!mkdir(public_path('assets/signature/cuti/pencatat/'), 0777, true)) {
                return response()->json(['success' => false, 'message' => 'Gagal membuat direktori untuk menyimpan tanda tangan.'], 500);
            }
        }
        
        // Decode data base64 dan simpan ke file
        if (file_put_contents($filePath, base64_decode($data)) === false) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan file tanda tangan.'], 500);
        }
        
        // Simpan data ke database
        $inserted = DataCuti::where('kode_cuti', $kode_cuti)
            ->update([
                'pencatat' => $pencatat,
                'ttd_pencatat' => $fileName,
            ]);

        if (!$inserted) {
            // Jika gagal menyimpan ke database
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data ke database.'], 500);
        }
        
        // Jika semua berhasil
        return response()->json(['success' => true, 'file' => $fileName]);
        
    }

    public function ttdPemohon(Request $request){
        // dd($request->all());
        $data = $request->input('picture');
        $kode_cuti = $request->input('kode_cuti');

        if (strpos($data, 'data:image/png;base64,') !== 0) {
            return response()->json(['success' => false, 'message' => 'Format gambar tidak valid.'], 400);
        }

        $data = str_replace('data:image/png;base64,', '', $data);

        if (!base64_decode($data, true)) {
            return response()->json(['success' => false, 'message' => 'Data base64 tidak valid.'], 400);
        }

        $fileName = 'pemohon-' . str_replace('/', '_', $kode_cuti) .'-' . time() . '.png';  
        $filePath = public_path('assets/signature/cuti/pemohon/') . $fileName;
        
        if (!file_exists(public_path('assets/signature/cuti/pemohon/'))) {
            if (!mkdir(public_path('assets/signature/cuti/pemohon/'), 0777, true)) {
                return response()->json(['success' => false, 'message' => 'Gagal membuat direktori untuk menyimpan tanda tangan.'], 500);
            }
        }
        
        // Decode data base64 dan simpan ke file
        if (file_put_contents($filePath, base64_decode($data)) === false) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan file tanda tangan.'], 500);
        }
        
        // Simpan data ke database
        $inserted = DataCuti::where('kode_cuti', $kode_cuti)
            ->update([
                'ttd_karyawan_pemohon' => $fileName,
            ]);

        if (!$inserted) {
            // Jika gagal menyimpan ke database
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data ke database.'], 500);
        }
        
        // Jika semua berhasil
        return response()->json(['success' => true, 'file' => $fileName]);
    }

    public function ttdPengganti(Request $request){
        // dd($request->all());
        $data = $request->input('picture');
        $kode_cuti = $request->input('kode_cuti');

        if (strpos($data, 'data:image/png;base64,') !== 0) {
            return response()->json(['success' => false, 'message' => 'Format gambar tidak valid.'], 400);
        }

        $data = str_replace('data:image/png;base64,', '', $data);

        if (!base64_decode($data, true)) {
            return response()->json(['success' => false, 'message' => 'Data base64 tidak valid.'], 400);
        }

        $fileName = 'pengganti-' . str_replace('/', '_', $kode_cuti) .'-' . time() . '.png';  
        $filePath = public_path('assets/signature/cuti/pengganti/') . $fileName;
        
        if (!file_exists(public_path('assets/signature/cuti/pengganti/'))) {
            if (!mkdir(public_path('assets/signature/cuti/pengganti/'), 0777, true)) {
                return response()->json(['success' => false, 'message' => 'Gagal membuat direktori untuk menyimpan tanda tangan.'], 500);
            }
        }
        
        // Decode data base64 dan simpan ke file
        if (file_put_contents($filePath, base64_decode($data)) === false) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan file tanda tangan.'], 500);
        }
        
        // Simpan data ke database
        $inserted = DataCuti::where('kode_cuti', $kode_cuti)
            ->update([
                'ttd_karyawan_pengganti' => $fileName,
            ]);

        if (!$inserted) {
            // Jika gagal menyimpan ke database
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data ke database.'], 500);
        }
        
        // Jika semua berhasil
        return response()->json(['success' => true, 'file' => $fileName]);
    }

    public function nullTtd(Request $request, string $kode_cuti)
    {
        $cuti_data = DataCuti::where('kode_cuti', $kode_cuti)->first();
        // dd($cuti_data);
        
        if ($request->ttd_mengetahui) {
            // Path lengkap ke file yang akan dihapus
            $filePath = public_path('assets/signature/cuti/mengetahui/' . $cuti_data->ttd_mengetahui);

            // Cek apakah file benar-benar ada sebelum menghapus
            if (file_exists($filePath) && is_file($filePath)) {
                try {
                    unlink($filePath);
                } catch (\Exception $e) {
                    // Jika gagal hapus, bisa ditangani/log error
                    Log::error('Gagal menghapus file Mengetahui: ' . $e->getMessage());
                }
            }
        }
        if ($request->ttd_menyetujui) {
            // Path lengkap ke file yang akan dihapus
            $filePath = public_path('assets/signature/cuti/menyetujui/' . $cuti_data->ttd_menyetujui);

            // Cek apakah file benar-benar ada sebelum menghapus
            if (file_exists($filePath) && is_file($filePath)) {
                try {
                    unlink($filePath);
                } catch (\Exception $e) {
                    // Jika gagal hapus, bisa ditangani/log error
                    Log::error('Gagal menghapus file ttd_persetujuan: ' . $e->getMessage());
                }
            }
        }
        if ($request->ttd_pencatat) {
            // Path lengkap ke file yang akan dihapus
            $filePath = public_path('assets/signature/cuti/pencatat/' . $cuti_data->ttd_pencatat);

            // Cek apakah file benar-benar ada sebelum menghapus
            if (file_exists($filePath) && is_file($filePath)) {
                try {
                    unlink($filePath);
                } catch (\Exception $e) {
                    // Jika gagal hapus, bisa ditangani/log error
                    Log::error('Gagal menghapus file ttd_pencatat: ' . $e->getMessage());
                }
            }
        }
        if ($request->ttd_karyawan_pengganti) {
            // Path lengkap ke file yang akan dihapus
            $filePath = public_path('assets/signature/cuti/karyawan_pengganti/' . $cuti_data->ttd_karyawan_pengganti);

            // Cek apakah file benar-benar ada sebelum menghapus
            if (file_exists($filePath) && is_file($filePath)) {
                try {
                    unlink($filePath);
                } catch (\Exception $e) {
                    // Jika gagal hapus, bisa ditangani/log error
                    Log::error('Gagal menghapus file ttd_karyawan_pengganti: ' . $e->getMessage());
                }
            }
        }
        if ($request->ttd_karyawan_pemohon) {
            // Path lengkap ke file yang akan dihapus
            $filePath = public_path('assets/signature/cuti/karyawan_pemohon/' . $cuti_data->ttd_karyawan_pemohon);

            // Cek apakah file benar-benar ada sebelum menghapus
            if (file_exists($filePath) && is_file($filePath)) {
                try {
                    unlink($filePath);
                } catch (\Exception $e) {
                    // Jika gagal hapus, bisa ditangani/log error
                    Log::error('Gagal menghapus file ttd_karyawan_pemohon: ' . $e->getMessage());
                }
            }
        }
        $cuti_data->update($request->all());
        return redirect()->route('perizinan.riwayat.cuti.show', $kode_cuti)->with('success', 'Cuti Berhasil diubah');
    }

    
    public function download(string $id)
    {
        $cuti = DataCuti::join('jenis_cutis', 'data_cutis.id_jenis_cuti', '=', 'jenis_cutis.id')
            ->join('employee_cutis', 'data_cutis.id_employee_cuti', '=', 'employee_cutis.id')
            ->join('employees', 'employee_cutis.employee_id', '=', 'employees.id')
            ->join('profesis', 'employees.profesi', '=', 'profesis.id')
            ->join('units', 'employees.jabatan_struktural', '=', 'units.id')
            ->select(
                'data_cutis.*',
                'employee_cutis.*',
                'employees.nama_lengkap',
                'employees.nip_karyawan',
                'employees.alamat_lengkap',
                'employee_cutis.tahun',
                'employee_cutis.periode',
                'jenis_cutis.nama_jenis_cuti',
                'profesis.nama_profesi',
                'units.nama_unit'
            )
            ->where('data_cutis.kode_cuti', $id)
            ->firstOrFail();
            // dd($cuti);

        // Ambil daftar tanggal cuti (jika ada)
        $tanggal_cuti = DB::table('tanggal_cutis')
            ->where('kode_cuti', $id)
            ->orderBy('tanggal_cuti', 'asc')
            ->pluck('tanggal_cuti')
            ->toArray();
        // // dd($cuti);
        $kops = DB::table('web_settings')->first();

        $logoPath = ltrim($kops->logo, '/'); // hapus slash depan kalau ada
        $path = public_path($logoPath);

        if (!file_exists($path)) {
            abort(404, 'File logo tidak ditemukan');
        }

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);


        if(isset($cuti->ttd_karyawan_pemohon)){
            $path1 = public_path('assets/signature/cuti/pemohon/'.$cuti->ttd_karyawan_pemohon);
            $type1 = pathinfo($path1, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path1);
            $pemohon = 'data:image/' . $type1 . ';base64,' . base64_encode($data1);
        }else{
            $pemohon = null;
        }

        if(isset($cuti->ttd_karyawan_pengganti)){
            $path2 = public_path('assets/signature/cuti/pengganti/'.$cuti->ttd_karyawan_pengganti);
            $type2 = pathinfo($path2, PATHINFO_EXTENSION);
            $data2 = file_get_contents($path2);
            $pengganti = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);
        }else{
            $pengganti = null;
        }

        if(isset($cuti->ttd_menyetujui)){
            $path3 = public_path('assets/signature/cuti/menyetujui/'.$cuti->ttd_menyetujui);
            $type3 = pathinfo($path3, PATHINFO_EXTENSION);
            $data3 = file_get_contents($path3);
            $menyetujui = 'data:image/' . $type3 . ';base64,' . base64_encode($data3);
        }else{
            $menyetujui = null;
        }

        if(isset($cuti->ttd_mengetahui)){
            $path4 = public_path('assets/signature/cuti/mengetahui/'.$cuti->ttd_mengetahui);
            $type4 = pathinfo($path4, PATHINFO_EXTENSION);
            $data4 = file_get_contents($path4);
            $mengetahui = 'data:image/' . $type4 . ';base64,' . base64_encode($data4);
        }else{
            $mengetahui = null;
        }

        if(isset($cuti->ttd_pencatat)){
            $path5 = public_path('assets/signature/cuti/pencatat/'.$cuti->ttd_pencatat);
            $type5 = pathinfo($path5, PATHINFO_EXTENSION);
            $data5 = file_get_contents($path5);
            $pencatat = 'data:image/' . $type5 . ';base64,' . base64_encode($data5);
        }else{
            $pencatat = null;
        }

        $html = view('perizinan.cuti.riwayat.printPdfFile',  compact(
            'cuti',
            'tanggal_cuti',
            'kops',
            'logo',
            'pemohon',
            'pengganti',
            'menyetujui',
            'mengetahui',
            'pencatat'
        ))->render();
        
        $encoding = mb_detect_encoding($html, mb_detect_order(), true);
        if ($encoding !== 'UTF-8') {
            $html = iconv($encoding, 'UTF-8//IGNORE', $html);
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('resume_' . $id . '.pdf', ['Attachment' => false]);
    }

}
