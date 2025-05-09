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
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            // Menyimpan gambar ke folder public/dokumen/dokumen_kontrak
            $request->file('dokumen')->move(public_path('dokumen/dokumen_kontrak'), $request->file('dokumen')->getClientOriginalName());
            $data['dokumen'] = 'dokumen/dokumen_kontrak/' . $request->file('dokumen')->getClientOriginalName();
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
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
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
