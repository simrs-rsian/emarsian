<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatRekrutmen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Employee\Employee;

class RiwayatRekrutmenController extends Controller
{
    public function show(Request $request, $riwayat_rekrutmen)
    {        
        $id_employee = $riwayat_rekrutmen;
        $riwayatRekrutmens = RiwayatRekrutmen::leftjoin('employees', 'riwayat_rekrutmens.id_employee', '=', 'employees.id')
            ->select('riwayat_rekrutmens.*', 'employees.nama_lengkap', 'employees.nip_karyawan')
            ->where('riwayat_rekrutmens.id_employee', $id_employee)
            ->get();
        $employee = Employee::find($id_employee);
        return view('riwayat.rekrutmen.show', compact('riwayatRekrutmens', 'employee'));
    }
    
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_riwayat' => 'required|string',
            'tanggal_riwayat' => 'required|date',
            'dokumen.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'id_employee' => 'required|exists:employees,id',
        ]);

        // Loop setiap dokumen yang diupload
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('dokumen/dokumen_rekrutmen'), $filename);

                RiwayatRekrutmen::create([
                    'id_employee' => $request->id_employee,
                    'nama_riwayat' => $request->nama_riwayat . ' - ' . $file->getClientOriginalName(),
                    'tanggal_riwayat' => $request->tanggal_riwayat,
                    'dokumen' => 'dokumen/dokumen_rekrutmen/' . $filename,
                ]);
            }
        } else {
            // Jika tidak upload dokumen, tetap simpan record (tanpa file)
            RiwayatRekrutmen::create([
                'id_employee' => $request->id_employee,
                'nama_riwayat' => $request->nama_riwayat,
                'tanggal_riwayat' => $request->tanggal_riwayat,
                'dokumen' => null,
            ]);
        }

        return redirect()
            ->route('riwayat_rekrutmen.show', ['riwayat_rekrutmen' => $request->id_employee])
            ->with('success', 'Riwayat Rekrutmen berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = RiwayatRekrutmen::findOrFail($id);
        $employee = Employee::find($riwayat->id_employee);
        return view('riwayat.rekrutmen.edit', compact('riwayat', 'employee'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $riwayat = RiwayatRekrutmen::findOrFail($id);

        $request->validate([
            'nama_riwayat' => 'required|string',
            'tanggal_riwayat' => 'required|date',
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
            $request->file('dokumen')->move(public_path('dokumen/dokumen_rekrutmen'), $newFileName);

            // Tambahkan path dokumen baru ke dalam array data
            $data['dokumen'] = 'dokumen/dokumen_rekrutmen/' . $newFileName;
        }

        // Update data Riwayat Rekrutmen
        // dd($data);
        $riwayat->update($data);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('riwayat_rekrutmen.show', ['riwayat_rekrutmen' => $riwayat->id_employee])->with('success', 'Riwayat Rekrutmen berhasil diperbarui.');
    }

    public function destroy(RiwayatRekrutmen $RiwayatRekrutmen)
    {
        if ($RiwayatRekrutmen->dokumen) {
            Storage::delete($RiwayatRekrutmen->dokumen);
        }

        $RiwayatRekrutmen->delete();
        
        return back()->with('success', 'Riwayat Rekrutmen berhasil dihapus.');
    }
}
