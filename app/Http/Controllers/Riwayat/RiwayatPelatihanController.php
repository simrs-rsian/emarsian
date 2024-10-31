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
    public function store(Request $request)
    {
        $request->validate([
            'id_pelatihan' => 'required|exists:pelatihans,id',
            'dokumen' => 'nullable|file',
            'id_employee' => 'required|exists:employees,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            // Menyimpan gambar ke folder public/dokumen/dokumen_pelatihan
            $request->file('dokumen')->move(public_path('dokumen/dokumen_pelatihan'), $request->file('dokumen')->getClientOriginalName());
            $employeeData['dokumen'] = 'dokumen/dokumen_pelatihan/' . $request->file('dokumen')->getClientOriginalName();
        }

        RiwayatPelatihan::create($data);

        // Menggunakan session flash message dan redirect ke halaman sebelumnya
        return back()->with('success', 'Riwayat Pelatihan berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pelatihan' => 'required|exists:pelatihans,id',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'id_employee' => 'required|exists:employees,id', // Validasi employee_id
        ]);
        // dd($request->all());

        $riwayat = RiwayatPelatihan::findOrFail($id);

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
        return back()->with('success', 'Riwayat Pelatihan berhasil diperbarui.');
    }

    public function destroy(RiwayatPelatihan $RiwayatPelatihan)
    {
        if ($RiwayatPelatihan->dokumen) {
            Storage::delete($RiwayatPelatihan->dokumen);
        }

        $RiwayatPelatihan->delete();
        
        return back()->with('success', 'Riwayat Pelatihan berhasil dihapus.');
    }

    public function show($id) {
        $pelatihans = DB::table('pelatihans as a')
            ->leftJoin('jenis_pelatihans as b', 'a.jenis_pelatihan_id', '=', 'b.id')
            ->select('a.*', 'b.nama_jenis')
            ->where('a.id',$id)
            ->first();  // Ambil satu data atau fail

        $pesertaPelatihans = DB::table('riwayat_pelatihans as a')
            ->leftJoin('pelatihans as b', 'a.id_pelatihan', '=', 'b.id')
            ->leftJoin('employees as c', 'a.id_employee', '=', 'c.id')
            ->leftJoin('jenis_pelatihans as d', 'b.jenis_pelatihan_id', '=', 'd.id')
            ->leftJoin('units as e', 'c.jabatan_struktural', '=', 'e.id')
            ->select('a.id as id_riwayat','b.id as id_pelatihan','b.poin','d.nama_jenis', 'c.*','e.nama_unit')
            ->where('b.id',$id)
            ->get();

        $employees = Employee::all();
        
        return view('pelatihan.directview', compact('pelatihans', 'employees','pesertaPelatihans'));
    }

    public function directstore(Request $request)
    {
        // Validasi data
        $request->validate([
            'id_pelatihan' => 'required|exists:pelatihans,id',
            'id_employee' => 'required|array', // pastikan input berupa array
            'id_employee.*' => 'exists:employees,id' // validasi setiap item di array ada di tabel employees
        ]);

        $idPelatihan = $request->input('id_pelatihan');
        $employeeIds = $request->input('id_employee');

        // Loop melalui setiap employee ID dan periksa duplikasi
        foreach ($employeeIds as $employeeId) {
            $exists = DB::table('riwayat_pelatihans')
                ->where('id_pelatihan', $idPelatihan)
                ->where('id_employee', $employeeId)
                ->exists();

            // Jika tidak ada duplikasi, insert data ke riwayat_pelatihans
            if (!$exists) {
                DB::table('riwayat_pelatihans')->insert([
                    'id_pelatihan' => $idPelatihan,
                    'id_employee' => $employeeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back()->with('success', 'Data Pegawai berhasil ditambahkan.');
    }
}
