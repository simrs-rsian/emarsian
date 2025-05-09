<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatLain;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class RiwayatLainController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_riwayat' => 'required|string',
            'tanggal_riwayat' => 'required|date',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            // Menyimpan gambar ke folder public/dokumen/dokumen_lain
            $request->file('dokumen')->move(public_path('dokumen/dokumen_lain'), $request->file('dokumen')->getClientOriginalName());
            $data['dokumen'] = 'dokumen/dokumen_lain/' . $request->file('dokumen')->getClientOriginalName();
        }

        RiwayatLain::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return back()->with('success', 'Riwayat Lain-Lain berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_riwayat' => 'required|string',
            'tanggal_riwayat' => 'required|date',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $riwayat = RiwayatLain::findOrFail($id);

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
            $request->file('dokumen')->move(public_path('dokumen/dokumen_lain'), $newFileName);

            // Tambahkan path dokumen baru ke dalam array data
            $data['dokumen'] = 'dokumen/dokumen_lain/' . $newFileName;
        }

        // Update data Riwayat Lain-Lain
        // dd($data);
        $riwayat->update($data);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Riwayat Lain-Lain berhasil diperbarui.');
    }

    public function destroy(RiwayatLain $RiwayatLain)
    {
        if ($RiwayatLain->dokumen) {
            Storage::delete($RiwayatLain->dokumen);
        }

        $RiwayatLain->delete();
        
        return back()->with('success', 'Riwayat Lain-Lain berhasil dihapus.');
    }
}
