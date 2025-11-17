<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatPendidikan;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // Import facade File

class RiwayatPendidikanController extends Controller
{
    public function show(Request $request, $riwayat_pendidikan)
    {        
        $id_employee = $riwayat_pendidikan;
        $riwayatPendidikans = RiwayatPendidikan::leftjoin('employees', 'riwayat_pendidikans.id_employee', '=', 'employees.id')
            ->select('riwayat_pendidikans.*', 'employees.nama_lengkap', 'employees.nip_karyawan')
            ->where('riwayat_pendidikans.id_employee', $id_employee)
            ->get();
        $employee = Employee::find($id_employee);
        return view('riwayat.pendidikan.show', compact('riwayatPendidikans', 'employee'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'tahun_masuk' => 'required|string',
            'tahun_lulus' => 'required|string',
            'nama_sekolah' => 'required',
            'lokasi' => 'required',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'jenis_data' => 'nullable|string',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();
        // dd($data);

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName(); // Nama file unik agar tidak bentrok
            $file->move(public_path('dokumen/dokumen_pendidikan'), $filename);
            $data['dokumen'] = 'dokumen/dokumen_pendidikan/' . $filename;
        }

        RiwayatPendidikan::create($data);

        return redirect()->route('riwayat_pendidikan.show', ['riwayat_pendidikan' => $request->id_employee])
            ->with('success', 'Riwayat Pendidikan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = RiwayatPendidikan::findOrFail($id);
        $employee = Employee::find($riwayat->id_employee);
        return view('riwayat.pendidikan.edit', compact('riwayat', 'employee'));
    }

    public function update(Request $request, $id)
    {
        // dd($id);
        $riwayat = RiwayatPendidikan::findOrFail($id);

        $request->validate([
            'tahun_masuk' => 'required|string',
            'tahun_lulus' => 'required|string',
            'nama_sekolah' => 'required|string',
            'lokasi' => 'required|string',
            'jenis_data' => 'nullable|string',
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
            $request->file('dokumen')->move(public_path('dokumen/dokumen_pendidikan'), $newFileName);

            // Tambahkan path dokumen baru ke dalam array data
            $data['dokumen'] = 'dokumen/dokumen_pendidikan/' . $newFileName;
        }

        $riwayat->update($data);

        return redirect()->route('riwayat_pendidikan.show', ['riwayat_pendidikan' => $riwayat->id_employee])->with('success', 'Riwayat Pendidikan berhasil diperbarui.');
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