<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatKeluarga;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // Import facade File
use App\Models\Employee\Employee;

class RiwayatKeluargaController extends Controller
{
    public function show(Request $request, $riwayat_keluarga)
    {        
        $id_employee = $riwayat_keluarga;
        $riwayatKeluargas = RiwayatKeluarga::leftjoin('employees', 'riwayat_keluargas.id_employee', '=', 'employees.id')
            ->select('riwayat_keluargas.*', 'employees.nama_lengkap', 'employees.nip_karyawan')
            ->where('riwayat_keluargas.id_employee', $id_employee)
            ->get();
        $employee = Employee::find($id_employee);
        return view('riwayat.keluarga.show', compact('riwayatKeluargas', 'employee'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_keluarga' => 'required|string',
            'status_keluarga' => 'required|string',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'jenis_data' => 'nullable|string',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName(); // Nama file unik agar tidak bentrok
            $file->move(public_path('dokumen/dokumen_keluarga'), $filename);
            $data['dokumen'] = 'dokumen/dokumen_keluarga/' . $filename;
        }

        RiwayatKeluarga::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return redirect()->route('riwayat_keluarga.show', ['riwayat_keluarga' => $request->id_employee])->with('success', 'Riwayat Keluarga berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = RiwayatKeluarga::findOrFail($id);
        $employee = Employee::find($riwayat->id_employee);
        return view('riwayat.keluarga.edit', compact('riwayat', 'employee'));
    }

    public function update(Request $request, $id)
    {
        $riwayat = RiwayatKeluarga::findOrFail($id);

        $request->validate([
            'nama_keluarga' => 'required|string',
            'status_keluarga' => 'required|string',
            'jenis_data' => 'nullable|string',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);
        // dd($request->all());

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
        return redirect()->route('riwayat_keluarga.show', ['riwayat_keluarga' => $riwayat->id_employee])->with('success', 'Riwayat Keluarga berhasil diperbarui.');
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
