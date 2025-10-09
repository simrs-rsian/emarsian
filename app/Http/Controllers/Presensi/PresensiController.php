<?php

namespace App\Http\Controllers\Presensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Presensi\RekapPresensi;
use App\Models\Presensi\JadwalPegawai;
use App\Models\Presensi\JamMasuk;
use App\Models\Presensi\TemporaryPresensi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use SebastianBergmann\Template\Template;
use Svg\Tag\Rect;

class PresensiController extends Controller
{
    public function index() {
        // Ambil pegawai berdasarkan departemen user
        $pegawai_datas = DB::connection('mysql2')
            ->table('pegawai as p')
            ->leftJoin('departemen as d', 'p.departemen', '=', 'd.dep_id')
            ->where('p.stts_aktif', 'AKTIF')
            ->select('p.id', 'p.nik', 'p.nama AS nama_pegawai', 'p.jbtn AS jabatan', 'd.nama AS nama_departemen')
            ->get();

            // dd($pegawai_datas);

        return view('presensi.index', compact('pegawai_datas'));
    }

    public function showSettingPresensi(Request $request, $id)
    {
        $currentDate = Carbon::now();

        // pastikan month & year selalu integer
        $month = (int)($request->input('month') ?? $currentDate->format('m'));
        $year = (int)($request->input('years') ?? $currentDate->format('Y'));

        $dateStart = Carbon::createFromDate($year, $month, 1)->startOfDay()->format('Y-m-d');
        $dateEnd   = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay()->format('Y-m-d');

        $pegawai = DB::connection('mysql2')
                    ->table('pegawai')
                    ->leftJoin('departemen', 'pegawai.departemen', '=', 'departemen.dep_id')
                    ->select('pegawai.*', 'departemen.nama AS nama_departemen')
                    ->where('id', $id)->first();
                    // dd($pegawai);

        $jadwal = JadwalPegawai::where('id', $id)
                    ->where('tahun', $year)
                    ->where('bulan', str_pad($month, 2, '0', STR_PAD_LEFT))
                    ->get();

        $sift = JamMasuk::select('shift', 'jam_masuk', 'jam_pulang')
                    ->get();

        return view('presensi.setting-presensi-show', compact('pegawai', 'jadwal', 'sift', 'month', 'year'));
    }

    public function updatePresensi(Request $request)
    {
        // dd($request->all());
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $hari = 'h' . $request->input('hari'); // contoh: h5
        $shift = $request->input('shift');
        $id     = $request->input('pegawai_id');

        if($shift == 'LIBUR') {
            DB::connection('mysql2')->table('jadwal_pegawai')
                ->where('id', $id)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->update([$hari => '']);
        }else {
            DB::connection('mysql2')->table('jadwal_pegawai')
                ->where('id', $id)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->update([$hari => $shift]);
        }

        return back()->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function setRiwayatPresensi(Request $request, $id)
    {
        // dd($request->all());
        $currentDate = Carbon::now();

        // pastikan month & year selalu integer
        $month = (int)($request->input('month') ?? $currentDate->format('m'));
        $year = (int)($request->input('years') ?? $currentDate->format('Y'));

        $dateStart = Carbon::createFromDate($year, $month, 1)->startOfDay()->format('Y-m-d');
        $dateEnd   = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay()->format('Y-m-d');

        $pegawai = DB::connection('mysql2')
                    ->table('pegawai')
                    ->leftJoin('departemen', 'pegawai.departemen', '=', 'departemen.dep_id')
                    ->select('pegawai.*', 'departemen.nama AS nama_departemen')
                    ->where('id', $id)->first();

        $data = DB::connection('mysql2')->table('rekap_presensi AS p')
            ->leftJoin('pegawai AS pg', 'p.id', '=', 'pg.id')
            ->leftJoin('jam_masuk AS jm', 'p.shift', '=', 'jm.shift')
            ->select('p.*', 'jm.jam_masuk as jam_masuk', 'jm.jam_pulang as jam_keluar', 'pg.id as id_pegawai')
            ->where('pg.id', $id)
            ->whereBetween('p.jam_datang', [$dateStart, $dateEnd])
            ->orderBy('p.jam_datang', 'ASC')
            ->get();
        
        $shifts = JamMasuk::select('shift AS nama_shift', 'jam_masuk', 'jam_pulang')->get();
        // dd($data);

        return view('presensi.setting-riwayat-presensi-show', compact('pegawai', 'data', 'month', 'year', 'shifts'));
    }

    public function storeRiwayatPresensi(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'tanggal' => 'required|date',
            'jam_datang' => 'required',
            'jam_pulang' => 'nullable',
            'keterangan' => 'nullable|string',
        ]);

        $pegawaiId = $request->input('id');
        $tanggal = Carbon::parse($request->input('tanggal'));
        $jamDatang = $request->input('jam_datang');
        $jamPulang = $request->input('jam_pulang');
        $keterangan = $request->input('keterangan') ?? '-';

        $tahun = $tanggal->year;
        $bulan = str_pad($tanggal->month, 2, '0', STR_PAD_LEFT);
        $hariKe = 'h' . $tanggal->day;

        // 🔹 Ambil shift dari jadwal_pegawai
        $jadwal = DB::connection('mysql2')
            ->table('jadwal_pegawai')
            ->where('id', $pegawaiId)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        if (!$jadwal || empty($jadwal->$hariKe)) {
            return back()->with('error', 'Shift pegawai untuk tanggal ini belum dijadwalkan.');
        }

        $shift = $jadwal->$hariKe;

        // 🔹 Ambil jam masuk dan jam pulang dari tabel jam_masuk
        $shiftData = JamMasuk::where('shift', $shift)->first();

        if (!$shiftData) {
            return back()->with('error', 'Data shift ' . $shift . ' tidak ditemukan di tabel jam_masuk.');
        }

        // 🔹 Bentuk waktu lengkap
        $jamDatangFull = Carbon::parse($tanggal->format('Y-m-d') . ' ' . $jamDatang);
        $jamPulangFull = $jamPulang ? Carbon::parse($tanggal->format('Y-m-d') . ' ' . $jamPulang) : null;

        // Jika jam pulang lebih kecil dari jam datang (shift malam)
        if ($jamPulangFull && $jamPulangFull->lessThan($jamDatangFull)) {
            $jamPulangFull->addDay();
        }

        // 🔹 Waktu masuk & pulang shift
        $jamMasukShift = Carbon::parse($tanggal->format('Y-m-d') . ' ' . $shiftData->jam_masuk);
        $jamPulangShift = Carbon::parse($tanggal->format('Y-m-d') . ' ' . $shiftData->jam_pulang);

        // dd($jamDatangFull, $jamPulangFull, $jamMasukShift, $jamPulangShift, $shift);

        // 🔹 Tentukan status & keterlambatan
        $status = 'Tepat Waktu';
        $keterlambatan = '-';

        if ($jamDatangFull->greaterThan($jamMasukShift)) {
            $menitTerlambat = $jamMasukShift->diffInMinutes($jamDatangFull);
            $keterlambatan = $menitTerlambat . ' menit';

            if ($menitTerlambat <= 5) {
                $status = 'Terlambat Toleransi';
            } elseif ($menitTerlambat <= 30) {
                $status = 'Terlambat I';
            } else {
                $status = 'Terlambat II';
            }
        }

        // 🔹 Tambahkan PSW jika pulang sebelum waktu shift
        if ($jamPulangFull && $jamPulangFull->lessThan($jamPulangShift)) {
            $status .= ' & PSW';
        }

        // 🔹 Hitung durasi kerja
        $durasi = $jamPulangFull
            ? $jamPulangFull->diff($jamDatangFull)->format('%H:%I:%S')
            : null;

        // dd($status, $keterlambatan, $durasi);

        // 🔹 Simpan ke rekap_presensi
        RekapPresensi::create([
            'id' => $pegawaiId,
            'shift' => $shift,
            'jam_datang' => $jamDatangFull->format('Y-m-d H:i:s'),
            'jam_pulang' => $jamPulangFull ? $jamPulangFull->format('Y-m-d H:i:s') : null,
            'status' => $status,
            'keterlambatan' => $keterlambatan,
            'durasi' => $durasi,
            'keterangan' => $keterangan,
            'photo'=> '-'
        ]);

        return back()->with('success', 'Riwayat presensi berhasil ditambahkan!');
    }

    public function updateRiwayatPresensi(Request $request)
    {
        $request->validate([
            'id' => 'required|integer', // id pegawai
            'jam_datang_id' => 'required', // id unik presensi (timestamp sebelumnya)
            'date' => 'required|date',
            'jam_datang' => 'required',
            'jam_pulang' => 'nullable',
            'keterangan' => 'nullable|string',
        ]);

        $pegawaiId = $request->input('id');
        $jamDatangId = $request->input('jam_datang_id');
        $tanggal = Carbon::parse($request->input('date'));
        $jamDatangInput = $request->input('jam_datang');
        $jamPulangInput = $request->input('jam_pulang');
        $keterangan = $request->input('keterangan') ?? '-';

        // 🔹 Ambil jadwal shift dari tabel jadwal_pegawai
        $tahun = $tanggal->year;
        $bulan = str_pad($tanggal->month, 2, '0', STR_PAD_LEFT);
        $hariKe = 'h' . $tanggal->day;

        $jadwal = DB::connection('mysql2')
            ->table('jadwal_pegawai')
            ->where('id', $pegawaiId)
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->first();

        if (!$jadwal || empty($jadwal->$hariKe)) {
            return back()->with('error', 'Shift pegawai untuk tanggal ini belum dijadwalkan.');
        }

        $shift = $jadwal->$hariKe;

        // 🔹 Ambil data jam masuk & jam pulang dari tabel jam_masuk
        $shiftData = JamMasuk::where('shift', $shift)->first();
        if (!$shiftData) {
            return back()->with('error', 'Data shift ' . $shift . ' tidak ditemukan di tabel jam_masuk.');
        }

        // 🔹 Bentuk waktu penuh (tanggal + jam)
        $jamDatang = Carbon::parse($tanggal->format('Y-m-d') . ' ' . $jamDatangInput);
        $jamPulang = $jamPulangInput ? Carbon::parse($tanggal->format('Y-m-d') . ' ' . $jamPulangInput) : null;

        // Jika shift malam (jam_pulang < jam_datang)
        if ($jamPulang && $jamPulang->lessThan($jamDatang)) {
            $jamPulang->addDay();
        }

        $jamMasukShift = Carbon::parse($tanggal->format('Y-m-d') . ' ' . $shiftData->jam_masuk);
        $jamPulangShift = Carbon::parse($tanggal->format('Y-m-d') . ' ' . $shiftData->jam_pulang);

        // 🔹 Hitung keterlambatan & status otomatis
        $status = 'Tepat Waktu';
        $keterlambatan = '-';

        if ($jamDatang->greaterThan($jamMasukShift)) {
            $menitTerlambat = $jamMasukShift->diffInMinutes($jamDatang);
            $keterlambatan = $menitTerlambat . ' menit';

            if ($menitTerlambat <= 5) {
                $status = 'Terlambat Toleransi';
            } elseif ($menitTerlambat <= 30) {
                $status = 'Terlambat I';
            } else {
                $status = 'Terlambat II';
            }
        }

        // 🔹 Tambahkan PSW jika pulang sebelum jam_pulang shift
        if ($jamPulang && $jamPulang->lessThan($jamPulangShift)) {
            $status .= ' & PSW';
        }

        // 🔹 Hitung durasi kerja
        $durasi = $jamPulang
            ? $jamPulang->diff($jamDatang)->format('%H:%I:%S')
            : null;

        // 🔹 Update ke rekap_presensi
        RekapPresensi::where('id', $pegawaiId)
            ->where('jam_datang', $jamDatangId)
            ->update([
                'jam_datang' => $jamDatang->format('Y-m-d H:i:s'),
                'jam_pulang' => $jamPulang ? $jamPulang->format('Y-m-d H:i:s') : null,
                'status' => $status,
                'keterlambatan' => $keterlambatan,
                'durasi' => $durasi,
                'keterangan' => $keterangan,
            ]);

        return back()->with('success', 'Data presensi berhasil diperbarui!');
    }

    public function hapusJamPulangRiwayatPresensi(Request $request)
    {
        // dd($request->all());
        $id = $request->input('id');
        $jam_datang = $request->input('jam_datang');
        // dd($id, $jam_datang);

        $rekap = RekapPresensi::where('id', $id)
            ->where('jam_datang', $jam_datang) // Pastikan jam_datang tidak null
            ->first();

            // dd($rekap);

        //ubah status
        $statusFiks = trim(str_replace(['& PSW', 'PSW'], '', $rekap->status));

        TemporaryPresensi::insert([
            'id' => $rekap->id,
            'shift' => $rekap->shift,
            'jam_datang' => $rekap->jam_datang,
            'jam_pulang' => null,
            'status' => $statusFiks,
            'keterlambatan' => $rekap->keterlambatan,
            'durasi' => '',
            'photo' => ''
        ]);

        RekapPresensi::where('id', $id)->where('jam_datang', $jam_datang)->delete();

        return back()->with('success', 'Jam pulang berhasil dihapus!');
    }

    public function absensiPegawai() {
        $absen_datas = TemporaryPresensi::leftJoin('pegawai', 'pegawai.id', '=', 'temporary_presensi.id')
            ->leftJoin('departemen', 'pegawai.departemen', '=', 'departemen.dep_id')
            ->leftJoin('jam_masuk AS shift', 'temporary_presensi.shift', '=', 'shift.shift')
            ->select('temporary_presensi.*', 'pegawai.nik', 'pegawai.nama', 'departemen.nama AS nama_departemen', 'shift.jam_masuk AS masuk', 'shift.jam_pulang AS keluar', 'pegawai.jbtn AS jabatan')
            ->get();
        $shifts = JamMasuk::select('shift AS nama_shift', 'jam_masuk', 'jam_pulang')->get();
        return view('presensi.absensi-pegawai', compact('absen_datas', 'shifts'));
    }

    public function verifyPresensi()
    {
        $id = request('id');

        // Ambil data dari temporary_presensi
        $verify_datas = TemporaryPresensi::select(
                'jam_masuk.jam_masuk as masuk',
                'jam_masuk.jam_pulang as keluar',
                'temporary_presensi.*'
            )
            ->leftJoin('jam_masuk', 'temporary_presensi.shift', '=', 'jam_masuk.shift')
            ->where('temporary_presensi.id', $id)
            ->first();

        if (!$verify_datas) {
            return back()->with('warning', 'Data tidak ditemukan di temporary presensi.');
        }

        $keluar = $verify_datas->keluar;
        $status = $verify_datas->status;
        $jam_datang = $verify_datas->jam_datang;
        $jam_pulang = now()->format('Y-m-d H:i:s');

        // Cek apakah data sudah ada di rekap_presensi
        $exists = RekapPresensi::where('id', $verify_datas->id)
            ->where('jam_datang', $jam_datang)
            ->exists();

        if ($exists) {
            return back()->with('warning', 'Data sudah ada/ tersimpan di rekap data');
        }

        // Tentukan status final
        if (strtotime($jam_pulang) < strtotime(date('Y-m-d') . ' ' . $keluar)) {
            $status_fiks = $status . ' & PSW'; // Pulang Sebelum Waktunya
        } else {
            $status_fiks = $status;
        }

        // Hitung durasi
        $durasi = date('H:i:s', strtotime($jam_pulang) - strtotime($jam_datang));

        // Simpan ke rekap_presensi
        RekapPresensi::insert([
            'id' => $verify_datas->id,
            'shift' => $verify_datas->shift,
            'jam_datang' => $jam_datang,
            'jam_pulang' => $jam_pulang,
            'status' => $status_fiks,
            'keterlambatan' => $verify_datas->keterlambatan,
            'durasi' => $durasi,
            'keterangan' => '-', // default
            'photo' => '-',
        ]);

        // Hapus dari temporary_presensi
        TemporaryPresensi::where('id', $id)->delete();

        return back()->with('success', 'Verifikasi presensi berhasil tersimpan!');
    }

    public function updateShiftPresensi(Request $request, $id) {
        // dd($request->all());

        $shift = $request->input('shift');
        $jam_datang = $request->input('jam_datang');
        $status = $request->input('status');
        $keterlambatan = $request->input('keterlambatan');
        
        TemporaryPresensi::where('id', $id)->update([
            'shift' => $shift,
            'jam_datang' => date('Y-m-d H:i:s', strtotime($jam_datang)),
            'status' => $status,
            'keterlambatan' => $keterlambatan ?? '-',
        ]);
        return back()->with('success', 'Shift berhasil diperbarui!');
    }
}
