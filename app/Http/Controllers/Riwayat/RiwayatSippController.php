<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatSipp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use App\Models\Employee\Employee;

class RiwayatSippController extends Controller
{
    public function show(Request $request, $riwayat_sipp)
    {        
        $id_employee = $riwayat_sipp;
        $riwayatSipps = RiwayatSipp::leftjoin('employees', 'riwayat_sipps.id_employee', '=', 'employees.id')
            ->select('riwayat_sipps.*', 'employees.nama_lengkap', 'employees.nip_karyawan')
            ->where('riwayat_sipps.id_employee', $id_employee)
            ->get();
        $employee = Employee::find($id_employee);
        return view('riwayat.sipp.show', compact('riwayatSipps', 'employee'));
    }
    
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'no_sipp' => 'required|string',
            'no_str' => 'required|string',
            'tanggal_berlaku' => 'required|date',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:15360',
            'id_employee' => 'required|exists:employees,id',
        ]);

        // Ambil semua input
        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName(); // Nama file unik agar tidak bentrok
            $file->move(public_path('dokumen/dokumen_sipp'), $filename);
            $data['dokumen'] = 'dokumen/dokumen_sipp/' . $filename;
        }

        RiwayatSipp::create($data);

        return redirect()->route('riwayat_sipp.show', ['riwayat_sipp' => $request->id_employee])
            ->with('success', 'Riwayat SIP berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = RiwayatSipp::findOrFail($id);
        $employee = Employee::find($riwayat->id_employee);
        return view('riwayat.sipp.edit', compact('riwayat', 'employee'));
    }


    public function update(Request $request, $id)
    {
        $riwayat = RiwayatSipp::findOrFail($id);

        $request->validate([
            'no_sipp' => 'required|string',
            'no_str' => 'required|string',
            'tanggal_berlaku' => 'required|date',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:15360',
        ]);

        // Mengambil data kecuali dokumen
        $data = $request->except(['dokumen', 'id_riwayat']);

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
        return redirect()->route('riwayat_sipp.show', ['riwayat_sipp' => $riwayat->id_employee])->with('success', 'Riwayat SIP berhasil diperbarui.');
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
