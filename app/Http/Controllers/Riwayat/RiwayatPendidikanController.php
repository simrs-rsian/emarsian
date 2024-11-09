<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatPendidikan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // Import facade File

class RiwayatPendidikanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tahun_masuk' => 'required|string',
            'tahun_lulus' => 'required|string',
            'nama_sekolah' => 'required',
            'lokasi' => 'required',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            // Menyimpan gambar ke folder public/dokumen/dokumen_pendidikan
            $request->file('dokumen')->move(public_path('dokumen/dokumen_pendidikan'), $request->file('dokumen')->getClientOriginalName());
            $employeeData['dokumen'] = 'dokumen/dokumen_pendidikan/' . $request->file('dokumen')->getClientOriginalName();
        }

        RiwayatPendidikan::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return back()->with('success', 'Riwayat Pendidikan berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_employee' => 'required|exists:employees,id', // Validasi id_employee
        ]);

        $riwayat = RiwayatPendidikan::findOrFail($id);

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
            $request->file('dokumen')->move(public_path('dokumen/dokumen_pendidikan'), $newFileName);

            // Tambahkan path dokumen baru ke dalam array data
            $data['dokumen'] = 'dokumen/dokumen_pendidikan/' . $newFileName;
        }

        // Update data riwayat pendidikan
        // dd($data);
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