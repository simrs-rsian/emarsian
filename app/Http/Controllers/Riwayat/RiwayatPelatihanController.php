<?php

namespace App\Http\Controllers\Riwayat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Riwayat\RiwayatPelatihan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use App\Models\Pelatihan\Pelatihan;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\DB;

class RiwayatPelatihanController extends Controller
{
    public function show($riwayat_pelatihan)
    {   
        $id_employee = $riwayat_pelatihan;
        $riwayatPelatihans = RiwayatPelatihan::leftjoin('employees', 'riwayat_pelatihans.id_employee', '=', 'employees.id')
            ->leftjoin('pelatihans', 'riwayat_pelatihans.id_pelatihan', '=', 'pelatihans.id')
            ->leftjoin('jenis_pelatihans', 'pelatihans.jenis_pelatihan_id', '=', 'jenis_pelatihans.id')
            ->select('riwayat_pelatihans.*', 'employees.nama_lengkap', 'employees.nip_karyawan', 'pelatihans.nama_pelatihan','pelatihans.tanggal_mulai', 'pelatihans.tanggal_selesai','pelatihans.penyelenggara','pelatihans.lokasi', 'pelatihans.poin', 'jenis_pelatihans.nama_jenis')
            ->where('riwayat_pelatihans.id_employee', $id_employee)
            ->get();
        $employee = Employee::find($id_employee);
        $pelatihans = Pelatihan::all();
        return view('riwayat.pelatihan.show', compact('riwayatPelatihans', 'employee', 'pelatihans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelatihan' => 'required|exists:pelatihans,id',
            'dokumen' => 'nullable|file',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName(); // Nama file unik agar tidak bentrok
            $file->move(public_path('dokumen/dokumen_pelatihan'), $filename);
            $data['dokumen'] = 'dokumen/dokumen_pelatihan/' . $filename;
        }

        RiwayatPelatihan::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return redirect()->route('riwayat_pelatihan.show', ['riwayat_pelatihan' => $request->id_employee])->with('success', 'Riwayat Pelatihan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = RiwayatPelatihan::findOrFail($id);
        $pelatihans = Pelatihan::all();
        $employee = Employee::find($riwayat->id_employee);
        return view('riwayat.pelatihan.edit', compact('riwayat', 'employee', 'pelatihans'));
    }


    public function update(Request $request, $id)
    {
        $riwayat = RiwayatPelatihan::findOrFail($id);

        $request->validate([
            'id_pelatihan' => 'required|exists:pelatihans,id',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'id_employee' => 'required|exists:employees,id', // Validasi employee_id
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
            $request->file('dokumen')->move(public_path('dokumen/dokumen_pelatihan'), $newFileName);

            // Tambahkan path dokumen baru ke dalam array data
            $data['dokumen'] = 'dokumen/dokumen_pelatihan/' . $newFileName;
        }
        // Update data Riwayat Pelatihan dengan data yang telah diproses
        $riwayat->update($data);

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('riwayat_pelatihan.show', ['riwayat_pelatihan' => $riwayat->id_employee])->with('success', 'Riwayat Pelatihan berhasil diperbarui.');
    }

    public function destroy(RiwayatPelatihan $RiwayatPelatihan)
    {
        if ($RiwayatPelatihan->dokumen) {
            Storage::delete($RiwayatPelatihan->dokumen);
        }

        $RiwayatPelatihan->delete();
        
        return back()->with('success', 'Riwayat Pelatihan berhasil dihapus.');
    }
}
