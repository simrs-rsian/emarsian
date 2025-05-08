<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\Pelatihan\Pelatihan;
use Carbon\Carbon;

class PegawaiController extends Controller
{
    //pegawai.profile
    public function profile()
    {
        $sessionid = session('id');
        $employee = Employee::select('employees.*', 
                         'status_karyawans.nama_status AS namastatuskar', 
                         'status_keluargas.nama_status AS namastatuskel', 
                         'pendidikans.nama_pendidikan', 
                         'profesis.nama_profesi', 
                         'units.nama_unit', 
                         'golongans.nama_golongan', 
                         'kelompok_umurs.nama_kelompok')
                    ->leftJoin('status_karyawans', 'employees.status_karyawan', '=', 'status_karyawans.id')
                    ->leftJoin('status_keluargas', 'employees.status_keluarga', '=', 'status_keluargas.id')
                    ->leftJoin('profesis', 'employees.profesi', '=', 'profesis.id')
                    ->leftJoin('pendidikans', 'employees.pendidikan', '=', 'pendidikans.id')
                    ->leftJoin('units', 'employees.jabatan_struktural', '=', 'units.id')
                    ->leftJoin('golongans', 'employees.golongan', '=', 'golongans.id')
                    ->leftJoin('kelompok_umurs', 'employees.kelompok_usia', '=', 'kelompok_umurs.id')
                    ->where('employees.id', $sessionid)
                    ->firstOrFail();
        $pendidikan = DB::table('riwayat_pendidikans as p')
                            ->leftJoin('employees as e', 'p.id_employee', '=', 'e.id')
                            ->select('p.*')
                            ->where('e.id', $employee->id)
                            ->get();
        $pelatihan = DB::table('riwayat_pelatihans as a')
                            ->leftJoin('pelatihans as b', 'a.id_pelatihan', '=', 'b.id')
                            ->leftJoin('employees as c', 'a.id_employee', '=', 'c.id')
                            ->leftJoin('jenis_pelatihans as d', 'b.jenis_pelatihan_id', '=', 'd.id')
                            ->select('a.*','b.*','d.nama_jenis')
                            ->where('c.id', $employee->id)
                            ->get();
        $jabatan = DB::table('riwayat_jabatans as j')
                            ->leftJoin('employees as e', 'j.id_employee', '=', 'e.id')
                            ->select('j.*')
                            ->where('e.id', $employee->id)
                            ->get();
        $keluarga = DB::table('riwayat_keluargas as k')
                            ->leftJoin('employees as e', 'k.id_employee', '=', 'e.id')
                            ->select('k.*')
                            ->where('e.id', $employee->id)
                            ->get();
        $sipp = DB::table('riwayat_sipps as s')
                            ->leftJoin('employees as e', 's.id_employee', '=', 'e.id')
                            ->select('s.*')
                            ->where('e.id', $employee->id)
                            ->get();
        $kontrak = DB::table('riwayat_kontraks as k')
                            ->leftJoin('employees as e', 'k.id_employee', '=', 'e.id')
                            ->select('k.*')
                            ->where('e.id', $employee->id)
                            ->get();
        $lain = DB::table('riwayat_lains as l')
                            ->leftJoin('employees as e', 'l.id_employee', '=', 'e.id')
                            ->select('l.*')
                            ->where('e.id', $employee->id)
                            ->get();
        
        $pelatihans = Pelatihan::all();
        return view('pegawai.profile', compact('employee', 'pendidikan', 'pelatihan', 'jabatan', 'keluarga', 'sipp', 'kontrak', 'lain', 'pelatihans'));
    }

    public function editProfile()
    {
        $sessionid = session('id');
        $employee = Employee::findOrFail($sessionid);
        return view('pegawai.edit-profile', compact('employee'));
    }

    public function updateProfile($id, Request $request)
    {
        $sessionid = $id;
        // dd($sessionid);
        $employee = Employee::findOrFail($sessionid);

        // Validasi
        $validated = $request->validate([
            'nik_karyawan' => 'required|string',
            'nama_lengkap' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string',
            'alamat_lengkap' => 'nullable|string',
            'telepon' => 'nullable|string',
            'golongan_darah' => 'nullable|string',
        ]);

        // Upload Foto (jika ada)
        // if ($request->hasFile('photo')) {
        //     $photoPath = $request->file('photo')->store('uploads/photo_karyawan', 'public');
        //     $validated['photo'] = 'storage/' . $photoPath;
        // }

        $employee->update($validated);

        return redirect()->route('pegawai.profile')->with('success', 'Profile berhasil diperbarui.');
    }


    public function riwayatPelatihan()
    {
        $sessionid = session('id');
        $pelatihan = DB::table('riwayat_pelatihans as a')
                            ->leftJoin('pelatihans as b', 'a.id_pelatihan', '=', 'b.id')
                            ->leftJoin('employees as c', 'a.id_employee', '=', 'c.id')
                            ->leftJoin('jenis_pelatihans as d', 'b.jenis_pelatihan_id', '=', 'd.id')
                            ->select('a.*','b.*','d.nama_jenis')    
                            ->where('c.id', $sessionid)
                            ->get();    
        return view('pegawai.riwayat.riwayat-pelatihan', compact('pelatihan'));
    }

    public function riwayatPendidikan()
    {
        $sessionid = session('id');
        $pendidikan = DB::table('riwayat_pendidikans as p')
                            ->leftJoin('employees as e', 'p.id_employee', '=', 'e.id')
                            ->select('p.*')
                            ->where('e.id', $sessionid)
                            ->get();
        return view('pegawai.riwayat.riwayat-pendidikan', compact('pendidikan'));
    }

    public function riwayatJabatan()
    {
        $sessionid = session('id');
        $jabatan = DB::table('riwayat_jabatans as j')
                            ->leftJoin('employees as e', 'j.id_employee', '=', 'e.id')
                            ->select('j.*')
                            ->where('e.id', $sessionid)
                            ->get();
        return view('pegawai.riwayat.riwayat-jabatan', compact('jabatan'));
    }

    public function riwayatKeluarga()
    {
        $sessionid = session('id');
        $keluarga = DB::table('riwayat_keluargas as k')
                            ->leftJoin('employees as e', 'k.id_employee', '=', 'e.id')
                            ->select('k.*')
                            ->where('e.id', $sessionid)
                            ->get();
        return view('pegawai.riwayat.riwayat-keluarga', compact('keluarga'));
    }

    public function riwayatSipp()
    {
        $sessionid = session('id');
        $sipp = DB::table('riwayat_sipps as s')
                            ->leftJoin('employees as e', 's.id_employee', '=', 'e.id')
                            ->select('s.*')
                            ->where('e.id', $sessionid)
                            ->get();
        return view('pegawai.riwayat.riwayat-sipp', compact('sipp'));
    }

    public function riwayatKontrak()
    {
        $sessionid = session('id');
        $kontrak = DB::table('riwayat_kontraks as k')
                            ->leftJoin('employees as e', 'k.id_employee', '=', 'e.id')
                            ->select('k.*')
                            ->where('e.id', $sessionid)
                            ->get();
        return view('pegawai.riwayat.riwayat-kontrak', compact('kontrak'));
    }

    public function riwayatLain()
    {
        $sessionid = session('id');
        $lain = DB::table('riwayat_lains as l')
                            ->leftJoin('employees as e', 'l.id_employee', '=', 'e.id')
                            ->select('l.*')
                            ->where('e.id', $sessionid)
                            ->get();
        return view('pegawai.riwayat.riwayat-lain', compact('lain'));
    }

    public function jadwalPresensi(Request $request)
    {
        // Ambil bulan & tahun dari request atau default ke saat ini
        $currentDate = Carbon::now();
        $month = $request->input('month') ?? $currentDate->format('m');
        $year = $request->input('years') ?? $currentDate->format('Y');

        $sessionNip = session('nip_pegawai');

        $data = DB::connection('mysql2')->table('jadwal_pegawai AS jp')
            ->leftJoin('pegawai AS p', 'jp.id', '=', 'p.id')
            ->where('p.nik', $sessionNip)
            ->where('jp.tahun', $year)
            ->where('jp.bulan', str_pad($month, 2, '0', STR_PAD_LEFT))
            ->first();

        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        $calendar = [];
        $week = array_fill(0, 7, null); // Minggu sampai Sabtu

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $dayOfWeek = $date->dayOfWeek; // 0 = Minggu, 6 = Sabtu

            $jadwalKey = 'h' . $day;
            $jadwal = $data->$jadwalKey ?? '-';

            $week[$dayOfWeek] = [
                'tanggal' => $day,
                'jadwal' => $jadwal
            ];

            // Tambahkan minggu ke kalender jika Sabtu atau hari terakhir bulan
            if ($dayOfWeek == 6 || $day == $daysInMonth) {
                $calendar[] = $week;
                $week = array_fill(0, 7, null); // Reset minggu baru
            }
        }

        return view('pegawai.presensi.jadwal-presensi', compact('calendar', 'month', 'year'));
    }
}
