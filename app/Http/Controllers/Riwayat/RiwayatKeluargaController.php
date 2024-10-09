<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatKeluarga;
use Illuminate\Support\Facades\Storage;

class RiwayatKeluargaController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'nama_keluarga' => 'required|string',
            'status_keluarga' => 'required|string',
            'pekerjaan_keluarga' => 'required|string',
            'pendidikan_keluarga' => 'required|string',
            'dokumen' => 'nullable|file',
            'id_employee' => 'required|exists:employees,id',
        ]);
        // dd($request->all());

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $dokumenPath = $request->file('dokumen')->store('dokumen/dokumen_keluarga', 'public');
            $data['dokumen'] = $dokumenPath;
        }

        RiwayatKeluarga::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return back()->with('success', 'Riwayat Keluarga berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_keluarga' => 'required|string',
            'status_keluarga' => 'required|string',
            'pekerjaan_keluarga' => 'required|string',
            'pendidikan_keluarga' => 'required|string',
            'dokumen' => 'nullable|file',
            'id_employee' => 'required|exists:employees,id', // Validasi employee_id
        ]);
        // dd($request->all());

        $riwayat = RiwayatKeluarga::findOrFail($id);

        // Mengambil data kecuali dokumen (jika diupload akan ditangani terpisah)
        $data = $request->except(['dokumen']);

        // Proses file dokumen jika diupload
        if ($request->hasFile('dokumen')) {
            // Hapus dokumen lama jika ada
            if ($riwayat->dokumen) {
                Storage::disk('public')->delete($riwayat->dokumen);
            }
            
            // Simpan dokumen baru
            $dokumenPath = $request->file('dokumen')->store('dokumen/dokumen_keluarga', 'public');
            $data['dokumen'] = $dokumenPath;
        }

        // Update data Riwayat Keluargan dengan data yang telah diproses
        $riwayat->update($data);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Riwayat Keluarga berhasil diperbarui.');
    }



    public function destroy(RiwayatKeluarga $RiwayatKeluarga)
    {
        if ($RiwayatKeluarga->dokumen) {
            Storage::disk('public')->delete($RiwayatKeluarga->dokumen);
        }

        $RiwayatKeluarga->delete();
        
        return back()->with('success', 'Riwayat Keluarga berhasil dihapus.');
    }
}
