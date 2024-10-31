<?php

namespace App\Http\Controllers\Pelatihan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelatihan\Pelatihan;
use App\Models\Pelatihan\JenisPelatihan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PelatihanController extends Controller
{
    public function index()
    {
        $pelatihans     = Pelatihan::select('pelatihans.*', 'jenis_pelatihans.nama_jenis')
                            ->join('jenis_pelatihans', 'pelatihans.jenis_pelatihan_id', 'jenis_pelatihans.id')
                            ->get();
        $jenispelatihans = JenisPelatihan::all();
        return view('pelatihan.pelatihan', compact('pelatihans', 'jenispelatihans'));
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
    
}
