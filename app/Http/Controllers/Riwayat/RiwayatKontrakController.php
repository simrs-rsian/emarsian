<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatKontrak;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Employee\Employee;

class RiwayatKontrakController extends Controller
{    
    public function show(Request $request, $riwayat_kontrak)
    {        
        $id_employee = $riwayat_kontrak;
        $RiwayatKontraks = RiwayatKontrak::leftjoin('employees', 'riwayat_kontraks.id_employee', '=', 'employees.id')
            ->select('riwayat_kontraks.*', 'employees.nama_lengkap', 'employees.nip_karyawan')
            ->where('riwayat_kontraks.id_employee', $id_employee)
            ->get();
        $employee = Employee::find($id_employee);
        return view('riwayat.kontrak.show', compact('RiwayatKontraks', 'employee'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName(); // Nama file unik agar tidak bentrok
            $file->move(public_path('dokumen/dokumen_kontrak'), $filename);
            $data['dokumen'] = 'dokumen/dokumen_kontrak/' . $filename;
        }

        RiwayatKontrak::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return redirect()->route('riwayat_kontrak.show', ['riwayat_kontrak' => $request->id_employee])->with('success', 'Riwayat Kontrak berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = RiwayatKontrak::findOrFail($id);
        $employee = Employee::find($riwayat->id_employee);
        return view('riwayat.kontrak.edit', compact('riwayat', 'employee'));
    }
    
    public function update(Request $request, $id)
    {
        $riwayat = RiwayatKontrak::findOrFail($id);

        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

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
        return redirect()->route('riwayat_kontrak.show', ['riwayat_kontrak' => $riwayat->id_employee])->with('success', 'Riwayat Kontrak berhasil diperbarui.');
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
