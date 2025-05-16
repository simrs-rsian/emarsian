<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatLain;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Employee\Employee;

class RiwayatLainController extends Controller
{
    public function show(Request $request, $riwayat_lain)
    {        
        $id_employee = $riwayat_lain;
        $riwayatLains = RiwayatLain::leftjoin('employees', 'riwayat_lains.id_employee', '=', 'employees.id')
            ->select('riwayat_lains.*', 'employees.nama_lengkap', 'employees.nip_karyawan')
            ->where('riwayat_lains.id_employee', $id_employee)
            ->get();
        $employee = Employee::find($id_employee);
        return view('riwayat.lain.show', compact('riwayatLains', 'employee'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_riwayat' => 'required|string',
            'tanggal_riwayat' => 'required|date',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName(); // Nama file unik agar tidak bentrok
            $file->move(public_path('dokumen/dokumen_lain'), $filename);
            $data['dokumen'] = 'dokumen/dokumen_lain/' . $filename;
        }

        RiwayatLain::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return redirect()->route('riwayat_lain.show', ['riwayat_lain' => $request->id_employee])->with('success', 'Riwayat Lain-Lain berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = RiwayatLain::findOrFail($id);
        $employee = Employee::find($riwayat->id_employee);
        return view('riwayat.lain.edit', compact('riwayat', 'employee'));
    }

    public function update(Request $request, $id)
    {
        $riwayat = RiwayatLain::findOrFail($id);

        $request->validate([
            'nama_riwayat' => 'required|string',
            'tanggal_riwayat' => 'required|date',
            'id_employee' => 'required|exists:employees,id',
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
            $request->file('dokumen')->move(public_path('dokumen/dokumen_lain'), $newFileName);

            // Tambahkan path dokumen baru ke dalam array data
            $data['dokumen'] = 'dokumen/dokumen_lain/' . $newFileName;
        }

        // Update data Riwayat Lain-Lain
        // dd($data);
        $riwayat->update($data);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('riwayat_lain.show', ['riwayat_lain' => $riwayat->id_employee])->with('success', 'Riwayat Lain-Lain berhasil diperbarui.');
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
