<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatJabatan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use App\Models\Employee\Employee;

class RiwayatJabatanController extends Controller
{    
    public function show(Request $request, $riwayat_jabatan)
    {        
        $id_employee = $riwayat_jabatan;
        $riwayatJabatans = RiwayatJabatan::leftjoin('employees', 'riwayat_jabatans.id_employee', '=', 'employees.id')
            ->select('riwayat_jabatans.*', 'employees.nama_lengkap', 'employees.nip_karyawan')
            ->where('riwayat_jabatans.id_employee', $id_employee)
            ->get();
        $employee = Employee::find($id_employee);
        return view('riwayat.jabatan.show', compact('riwayatJabatans', 'employee'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_mulai' => 'required|string',
            'tahun_selesai' => 'required|string',
            'keterangan' => 'required|string',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName(); // Nama file unik agar tidak bentrok
            $file->move(public_path('dokumen/dokumen_jabatan'), $filename);
            $data['dokumen'] = 'dokumen/dokumen_jabatan/' . $filename;
        }

        RiwayatJabatan::create($data);

        return redirect()->route('riwayat_jabatan.show', ['riwayat_jabatan' => $request->id_employee])
            ->with('success', 'Riwayat Pendidikan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = RiwayatJabatan::findOrFail($id);
        $employee = Employee::find($riwayat->id_employee);
        return view('riwayat.jabatan.edit', compact('riwayat', 'employee'));
    }

    public function update(Request $request, $id)
    {
        $riwayat = RiwayatJabatan::findOrFail($id);

        $request->validate([
            'tahun_mulai' => 'required|string',
            'tahun_selesai' => 'required|string',
            'keterangan' => 'required|string',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

        $data = $request->except(['dokumen', 'id_riwayat']);

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
        return redirect()->route('riwayat_jabatan.show', ['riwayat_jabatan' => $riwayat->id_employee])->with('success', 'Riwayat Jabatan berhasil diperbarui.');
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
