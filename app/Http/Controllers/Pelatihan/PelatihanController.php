<?php

namespace App\Http\Controllers\Pelatihan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelatihan\Pelatihan;
use App\Models\Pelatihan\JenisPelatihan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\File;

class PelatihanController extends Controller
{
    public function index()
    {
        $pelatihans     = Pelatihan::select('pelatihans.*', 'jenis_pelatihans.nama_jenis')
                            ->join('jenis_pelatihans', 'pelatihans.jenis_pelatihan_id', 'jenis_pelatihans.id')
                            ->get();
        $jenispelatihans = JenisPelatihan::all();
        return view('pelatihan.index', compact('pelatihans', 'jenispelatihans'));
    }    

    public function show($id) {
        $pelatihans = DB::table('pelatihans as a')
            ->leftJoin('jenis_pelatihans as b', 'a.jenis_pelatihan_id', '=', 'b.id')
            ->select('a.*', 'b.nama_jenis')
            ->where('a.id',$id)
            ->first();  // Ambil satu data atau fail

        $pesertaPelatihans = DB::table('riwayat_pelatihans as a')
            ->leftJoin('pelatihans as b', 'a.id_pelatihan', '=', 'b.id')
            ->leftJoin('employees as c', 'a.id_employee', '=', 'c.id')
            ->leftJoin('jenis_pelatihans as d', 'b.jenis_pelatihan_id', '=', 'd.id')
            ->leftJoin('units as e', 'c.jabatan_struktural', '=', 'e.id')
            ->select('a.id as id_riwayat','b.id as id_pelatihan','b.poin','d.nama_jenis', 'c.*','e.nama_unit')
            ->where('b.id',$id)
            ->get();

        $employees = Employee::all();
        
        return view('pelatihan.directview', compact('pelatihans', 'employees','pesertaPelatihans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelatihan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'penyelenggara' => 'required|string',
            'lokasi' => 'required|string',
            'poin' => 'required|string',
            'jenis_pelatihan_id' => 'required|exists:jenis_pelatihans,id',
        ]);

        $data = $request->all();

        Pelatihan::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return back()->with('success', 'Data Pelatihan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelatihan' => 'sometimes|required|string',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_selesai' => 'sometimes|required|date',
            'penyelenggara' => 'sometimes|required|string',
            'lokasi' => 'sometimes|required|string',
            'poin' => 'sometimes|required|string',
            'jenis_pelatihan_id' => 'sometimes|required|exists:jenis_pelatihans,id',
        ]);

        // Mencari data pelatihan berdasarkan ID
        $pelatihan = Pelatihan::findOrFail($id);

        // Memperbarui data pelatihan
        $pelatihan->update($request->all());

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return back()->with('success', 'Data Pelatihan berhasil diperbarui.');
    }

    public function destroy(Pelatihan $Pelatihan)
    {
        if ($Pelatihan->dokumen) {
            Storage::delete($Pelatihan->dokumen);
        }

        $Pelatihan->delete();
        
        return back()->with('success', 'Pelatihan berhasil dihapus.');
    }

    public function report(Request $request)
    {
        // Mengatur tahun default ke tahun saat ini jika tidak ada input
        $tahun = $request->input('tahun', date('Y'));
    
        // Ambil semua pelatihan
        $dataPelatihan = DB::table('pelatihans')
            ->select('id', 'nama_pelatihan', 'poin', 'tanggal_mulai')
            ->when($tahun, function ($query) use ($tahun) {
                return $query->whereYear('tanggal_mulai', $tahun);
            })
            ->get();
    
        // Ambil semua pegawai
        $pegawai = DB::table('employees')->pluck('nama_lengkap', 'id')->toArray();
        
        // Ambil riwayat pelatihan
        $riwayatPelatihan = DB::table('riwayat_pelatihans')
            ->leftJoin('pelatihans', 'riwayat_pelatihans.id_pelatihan', '=', 'pelatihans.id')
            ->select('riwayat_pelatihans.id_employee', 'pelatihans.nama_pelatihan', 'pelatihans.poin')
            ->when($tahun, function ($query) use ($tahun) {
                return $query->whereYear('pelatihans.tanggal_mulai', $tahun);
            })
            ->get();
    
        // Susun data pegawai
        $pegawaiData = [];
        foreach ($riwayatPelatihan as $item) {
            $pegawaiData[$item->id_employee]['pelatihan'][$item->nama_pelatihan] = $item->poin;
            $pegawaiData[$item->id_employee]['total'] = ($pegawaiData[$item->id_employee]['total'] ?? 0) + $item->poin;
        }
    
        // Menambahkan pencapaian: 1 jika total poin > 20, 0 jika tidak
        foreach ($pegawaiData as &$data) {
            $data['pencapaian'] = $data['total'] > 20 ? 1 : 0;
        }
    
        // Hitung total poin dan total pencapaian
        $totalPoin = array_sum(array_column($pegawaiData, 'total'));
        $totalPencapaian = array_sum(array_column($pegawaiData, 'pencapaian'));
        $jumlahPegawai = count($pegawai); // Hitung jumlah pegawai
    
        return view('pelatihan.report', compact('pegawai', 'dataPelatihan', 'pegawaiData', 'tahun', 'totalPoin', 'totalPencapaian', 'jumlahPegawai'));
    }

    public function directstore(Request $request)
    {
        // Validasi data
        $request->validate([
            'id_pelatihan' => 'required|exists:pelatihans,id',
            'id_employee' => 'required|array', // pastikan input berupa array
            'id_employee.*' => 'exists:employees,id' // validasi setiap item di array ada di tabel employees
        ]);

        $idPelatihan = $request->input('id_pelatihan');
        $employeeIds = $request->input('id_employee');

        // Loop melalui setiap employee ID dan periksa duplikasi
        foreach ($employeeIds as $employeeId) {
            $exists = DB::table('riwayat_pelatihans')
                ->where('id_pelatihan', $idPelatihan)
                ->where('id_employee', $employeeId)
                ->exists();

            // Jika tidak ada duplikasi, insert data ke riwayat_pelatihans
            if (!$exists) {
                DB::table('riwayat_pelatihans')->insert([
                    'id_pelatihan' => $idPelatihan,
                    'id_employee' => $employeeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back()->with('success', 'Data Pegawai berhasil ditambahkan.');
    }
    
}
