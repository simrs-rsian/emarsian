<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatSipp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 

class RiwayatSippController extends Controller
{
    
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'no_sipp' => 'required|string',
            'no_str' => 'required|string',
            'tanggal_berlaku' => 'required|date',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_employee' => 'required|exists:employees,id',
        ]);

        // Ambil semua input
        $data = $request->all();

        // Cek jika dokumen diunggah
        if ($request->hasFile('dokumen')) {
            // Tentukan path penyimpanan dokumen
            $path = public_path('dokumen/dokumen_sipp');
            $fileName = time() . '_' . $request->file('dokumen')->getClientOriginalName();
            
            // Simpan file ke folder yang ditentukan
            $request->file('dokumen')->move($path, $fileName);
            
            // Masukkan path dokumen ke array $data untuk disimpan di database
            $data['dokumen'] = 'dokumen/dokumen_sipp/' . $fileName;
        }

        RiwayatSipp::create($data);
        
        return back()->with('success', 'Riwayat Sipp berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'no_sipp' => 'required|string',
            'no_str' => 'required|string',
            'tanggal_berlaku' => 'required|date',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $riwayat = RiwayatSipp::findOrFail($id);

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
            $request->file('dokumen')->move(public_path('dokumen/dokumen_sipp'), $newFileName);

            // Tambahkan path dokumen baru ke dalam array data
            $data['dokumen'] = 'dokumen/dokumen_sipp/' . $newFileName;
        }

        // Update data Riwayat Sipp
        // dd($data);
        $riwayat->update($data);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Riwayat Sipp berhasil diperbarui.');
    }

    public function destroy(RiwayatSipp $RiwayatSipp)
    {
        if ($RiwayatSipp->dokumen) {
            Storage::delete($RiwayatSipp->dokumen);
        }

        $RiwayatSipp->delete();
        
        return back()->with('success', 'Riwayat Sipp berhasil dihapus.');
    }
}
