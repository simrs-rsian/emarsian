<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatKeluarga;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // Import facade File

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
            // Menyimpan gambar ke folder public/dokumen/dokumen_keluarga
            $request->file('dokumen')->move(public_path('dokumen/dokumen_keluarga'), $request->file('dokumen')->getClientOriginalName());
            $employeeData['dokumen'] = 'dokumen/dokumen_keluarga/' . $request->file('dokumen')->getClientOriginalName();
        }

        RiwayatKeluarga::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return back()->with('success', 'Riwayat Keluarga berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'id_employee' => 'required|exists:employees,id', // Validasi employee_id
        ]);
        // dd($request->all());

        $riwayat = RiwayatKeluarga::findOrFail($id);

        // Mengambil data kecuali dokumen (jika diupload akan ditangani terpisah)
        $data = $request->except(['dokumen']);

        // Proses file dokumen jika diupload
        if ($request->hasFile('dokumen')) {
            // Hapus dokumen lama jika ada
            if ($riwayat->dokumen && File::exists(public_path($riwayat->dokumen))) {
                File::delete(public_path($riwayat->dokumen));
            }

            // Menyimpan dokumen baru
            $newFileName = $request->file('dokumen')->getClientOriginalName();
            $request->file('dokumen')->move(public_path('dokumen/dokumen_keluarga'), $newFileName);

            // Tambahkan path dokumen baru ke dalam array data
            $data['dokumen'] = 'dokumen/dokumen_keluarga/' . $newFileName;
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
