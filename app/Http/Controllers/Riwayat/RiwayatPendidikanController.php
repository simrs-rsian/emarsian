<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatPendidikan;
use Illuminate\Support\Facades\Storage;

class RiwayatPendidikanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tahun_masuk' => 'required|string',
            'tahun_lulus' => 'required|string',
            'nama_sekolah' => 'required|string',
            'lokasi' => 'required|string',
            'dokumen' => 'nullable|file',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $dokumenPath = $request->file('dokumen')->store('dokumen/dokumen_pendidikan', 'public');
            $data['dokumen'] = $dokumenPath;
        }

        RiwayatPendidikan::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return back()->with('success', 'Riwayat Pendidikan berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun_masuk' => 'required|string',
            'tahun_lulus' => 'required|string',
            'nama_sekolah' => 'required|string',
            'lokasi' => 'required|string',
            'dokumen' => 'nullable|file',
            'id_employee' => 'required|exists:employees,id', // Validasi employee_id
        ]);
        // dd($request->all());

        $riwayat = RiwayatPendidikan::findOrFail($id);

        // Mengambil data kecuali dokumen (jika diupload akan ditangani terpisah)
        $data = $request->except(['dokumen']);

        // Proses file dokumen jika diupload
        if ($request->hasFile('dokumen')) {
            // Hapus dokumen lama jika ada
            if ($riwayat->dokumen) {
                Storage::disk('public')->delete($riwayat->dokumen);
            }
            
            // Simpan dokumen baru
            $dokumenPath = $request->file('dokumen')->store('dokumen/dokumen_pendidikan', 'public');
            $data['dokumen'] = $dokumenPath;
        }

        // Update data riwayat pendidikan dengan data yang telah diproses
        $riwayat->update($data);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Riwayat Pendidikan berhasil diperbarui.');
    }



    public function destroy(RiwayatPendidikan $riwayatPendidikan)
    {
        if ($riwayatPendidikan->dokumen) {
            Storage::delete($riwayatPendidikan->dokumen);
        }

        $riwayatPendidikan->delete();
        
        return back()->with('success', 'Riwayat Pendidikan berhasil dihapus.');
    }
}