<?php

namespace App\Http\Controllers\Pelatihan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelatihan\Pelatihan;
use App\Models\Pelatihan\JenisPelatihan;
use Illuminate\Support\Facades\Storage;

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
}
