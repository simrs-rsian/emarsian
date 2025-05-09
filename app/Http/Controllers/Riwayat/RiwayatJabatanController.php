<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatJabatan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 

class RiwayatJabatanController extends Controller
{    
    public function store(Request $request)
    {
        $request->validate([
            'tahun_mulai' => 'required|string',
            'tahun_selesai' => 'required|string',
            'keterangan' => 'required|string',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            // Menyimpan gambar ke folder public/dokumen/dokumen_jabatan
            $request->file('dokumen')->move(public_path('dokumen/dokumen_jabatan'), $request->file('dokumen')->getClientOriginalName());
            $data['dokumen'] = 'dokumen/dokumen_jabatan/' . $request->file('dokumen')->getClientOriginalName();
        }

        RiwayatJabatan::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return back()->with('success', 'Riwayat Jabatan berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun_mulai' => 'required|string',
            'tahun_selesai' => 'required|string',
            'keterangan' => 'required|string',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_employee' => 'required|exists:employees,id', 
        ]);

        $riwayat = RiwayatJabatan::findOrFail($id);

        $data = $request->except(['dokumen']);

        // Proses file dokumen jika diupload
        if ($request->hasFile('dokumen')) {
            // Hapus dokumen lama jika ada
            if ($riwayat->dokumen && File::exists(public_path($riwayat->dokumen))) {
                File::delete(public_path($riwayat->dokumen));
            }

            // Menyimpan dokumen baru
            $newFileName = $request->file('dokumen')->getClientOriginalName();
            $request->file('dokumen')->move(public_path('dokumen/dokumen_jabatan'), $newFileName);

            // Tambahkan path dokumen baru ke dalam array data
            $data['dokumen'] = 'dokumen/dokumen_jabatan/' . $newFileName;
        }
        // Update data Riwayat Pelatihan dengan data yang telah diproses
        $riwayat->update($data);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Riwayat Jabatan berhasil diperbarui.');
    }

    public function destroy(RiwayatJabatan $RiwayatJabatan)
    {
        if ($RiwayatJabatan->dokumen) {
            Storage::delete($RiwayatJabatan->dokumen);
        }

        $RiwayatJabatan->delete();
        
        return back()->with('success', 'Riwayat Jabatan berhasil dihapus.');
    }
}
