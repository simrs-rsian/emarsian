<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatPelatihan;
use Illuminate\Support\Facades\Storage;

class RiwayatPelatihanController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelatihan' => 'required|string',
            'mulai' => 'required|date',
            'selesai' => 'required|date',
            'penyelenggara' => 'required|string',
            'lokasi' => 'required|string',
            'dokumen' => 'nullable|file',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $dokumenPath = $request->file('dokumen')->store('dokumen/dokumen_pelatihan', 'public');
            $data['dokumen'] = $dokumenPath;
        }

        RiwayatPelatihan::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return back()->with('success', 'Riwayat Pelatihan berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelatihan' => 'required|string',
            'mulai' => 'required|date',
            'selesai' => 'required|date',
            'penyelenggara' => 'required|string',
            'lokasi' => 'required|string',
            'dokumen' => 'nullable|file',
            'id_employee' => 'required|exists:employees,id', // Validasi employee_id
        ]);
        // dd($request->all());

        $riwayat = RiwayatPelatihan::findOrFail($id);

        // Mengambil data kecuali dokumen (jika diupload akan ditangani terpisah)
        $data = $request->except(['dokumen']);

        // Proses file dokumen jika diupload
        if ($request->hasFile('dokumen')) {
            // Hapus dokumen lama jika ada
            if ($riwayat->dokumen) {
                Storage::disk('public')->delete($riwayat->dokumen);
            }
            
            // Simpan dokumen baru
            $dokumenPath = $request->file('dokumen')->store('dokumen/dokumen_pelatihan', 'public');
            $data['dokumen'] = $dokumenPath;
        }

        // Update data Riwayat Pelatihan dengan data yang telah diproses
        $riwayat->update($data);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Riwayat Pelatihan berhasil diperbarui.');
    }



    public function destroy(RiwayatPelatihan $RiwayatPelatihan)
    {
        if ($RiwayatPelatihan->dokumen) {
            Storage::delete($RiwayatPelatihan->dokumen);
        }

        $RiwayatPelatihan->delete();
        
        return back()->with('success', 'Riwayat Pelatihan berhasil dihapus.');
    }
}
