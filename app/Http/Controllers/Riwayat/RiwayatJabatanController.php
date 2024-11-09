<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatJabatan;
use Illuminate\Support\Facades\Storage;

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
            $dokumenPath = $request->file('dokumen')->store('dokumen/dokumen_jabatan', 'public');
            $data['dokumen'] = $dokumenPath;
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
        // dd($request->all());

        $riwayat = RiwayatJabatan::findOrFail($id);

        // Mengambil data kecuali dokumen (jika diupload akan ditangani terpisah)
        $data = $request->all();

        // Proses file dokumen jika diupload
        if ($request->hasFile('dokumen')) {
            // Hapus dokumen lama jika ada
            if ($riwayat->dokumen) {
                Storage::disk('public')->delete($riwayat->dokumen);
            }
            
            // Simpan dokumen baru
            $dokumenPath = $request->file('dokumen')->store('dokumen/dokumen_jabatan', 'public');
            $d['dokumen'] = $dokumenPath;
        }

        // Update data riwayat Jabatan dengan data yang telah diproses
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
