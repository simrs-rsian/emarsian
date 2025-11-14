<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatStr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Employee\Employee;

class RiwayatStrController extends Controller
{
    public function show(Request $request, $id)
    {        
        $id_employee = $id;

        $riwayatStrs = RiwayatStr::leftJoin('employees', 'riwayat_strs.id_employee', '=', 'employees.id')
            ->select('riwayat_strs.*', 'employees.nama_lengkap', 'employees.nip_karyawan')
            ->where('riwayat_strs.id_employee', $id_employee)
            ->get();

        $employee = Employee::find($id_employee);

        return view('riwayat.str.show', compact('riwayatStrs', 'employee'));
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
                $file->move(public_path('dokumen/dokumen_strs'), $filename);

                RiwayatStr::create([
                    'id_employee' => $request->id_employee,
                    'nama_riwayat' => $request->nama_riwayat . ' - ' . $file->getClientOriginalName(),
                    'tanggal_riwayat' => $request->tanggal_riwayat,
                    'dokumen' => 'dokumen/dokumen_strs/' . $filename,
                ]);
            }
        } else {
            // Jika tidak upload dokumen, tetap simpan record (tanpa file)
            RiwayatStr::create([
                'id_employee' => $request->id_employee,
                'nama_riwayat' => $request->nama_riwayat,
                'tanggal_riwayat' => $request->tanggal_riwayat,
                'dokumen' => null,
            ]);
        }

        return redirect()
            ->route('riwayat_str.show', ['riwayat_str' => $request->id_employee])
            ->with('success', 'Riwayat STR berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = RiwayatStr::findOrFail($id);
        $employee = Employee::find($riwayat->id_employee);
        return view('riwayat.str.edit', compact('riwayat', 'employee'));
    }

    public function update(Request $request, $riwayat_str)
    {
        // dd($request->all());
        $riwayat = RiwayatStr::findOrFail($riwayat_str);
        // dd($riwayat->id_employee);

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
            $request->file('dokumen')->move(public_path('dokumen/dokumen_strs'), $newFileName);

            // Tambahkan path dokumen baru ke dalam array data
            $data['dokumen'] = 'dokumen/dokumen_strs/' . $newFileName;
        }

        // Update data Riwayat STR
        // dd($data);
        $riwayat->update($data);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('riwayat_str.show', ['riwayat_str' => $riwayat->id_employee])->with('success', 'Riwayat STR berhasil diperbarui.');
    }

    public function destroy(RiwayatStr $RiwayatStr)
    {
        // dd($RiwayatStr->dokumen);
        if ($RiwayatStr->dokumen) {
            Storage::delete($RiwayatStr->dokumen);
        }

        $RiwayatStr->delete();
        
        return back()->with('success', 'Riwayat STR berhasil dihapus.');
    }
}
