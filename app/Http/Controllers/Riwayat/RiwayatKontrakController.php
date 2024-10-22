<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatKontrak;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class RiwayatKontrakController extends Controller
{    
    
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        

        // Cek jika dokumen diunggah
        if ($request->hasFile('dokumen')) {
            // Tentukan path penyimpanan dokumen
            $path = public_path('dokumen/dokumen_kontrak');
            $fileName = time() . '_' . $request->file('dokumen')->getClientOriginalName();
            
            // Simpan file ke folder yang ditentukan
            $request->file('dokumen')->move($path, $fileName);
            
            // Masukkan path dokumen ke array $data untuk disimpan di database
            $data['dokumen'] = 'dokumen/dokumen_kontrak/' . $fileName;
        }

        RiwayatKontrak::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return back()->with('success', 'Riwayat Kontrak berhasil ditambahkan.');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $riwayat = RiwayatKontrak::findOrFail($id);

        // Mengambil data kecuali dokumen
        $data = $request->except(['dokumen']);

        // Proses file dokumen jika diupload
        if ($request->hasFile('dokumen')) {
            // Hapus dokumen lama jika ada
            if ($riwayat->dokumen && File::exists(public_path($riwayat->dokumen))) {
                File::delete(public_path($riwayat->dokumen));
            }

            // Menyimpan dokumen baru
            $newFileName = $request->file('dokumen')->getClientOriginalName();
            $request->file('dokumen')->move(public_path('dokumen/dokumen_kontrak'), $newFileName);

            // Tambahkan path dokumen baru ke dalam array data
            $data['dokumen'] = 'dokumen/dokumen_kontrak/' . $newFileName;
        }

        // Update data Riwayat Kontrak
        // dd($data);
        $riwayat->update($data);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Riwayat Kontrak berhasil diperbarui.');
    }

    public function destroy(RiwayatKontrak $RiwayatKontrak)
    {
        if ($RiwayatKontrak->dokumen) {
            Storage::delete($RiwayatKontrak->dokumen);
        }

        $RiwayatKontrak->delete();
        
        return back()->with('success', 'Riwayat Kontrak berhasil dihapus.');
    }
}
