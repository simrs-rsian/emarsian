<?php

namespace App\Exports;

use App\Models\Perizinan\Cuti\DataCuti;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use App\Models\Perizinan\Cuti\EmployeeCuti;
use Carbon\Carbon;

class EmployeeCutiDataExport implements WithHeadings, FromArray
{
    public function headings(): array
    {
        // dd( request()->all() );
        return [
            ['No', 'NIP', 'Nama', 'Jabatan', 'Tanggal Cuti', 'Periode', 'Jumlah Hari', 'Pengajuan Cuti', 'Jenis Cuti', 'Keterangan', 'Sisa Cuti'],
        ];
    }

    public function array(): array
    {
        $tahun = request()->query('tahun');
        $bulan = request()->query('bulan');

        // Tentukan periode
        if ($bulan >= 1 && $bulan <= 6) {
            $periode = 1;
        } elseif ($bulan >= 7 && $bulan <= 12) {
            $periode = 2;
        } else {
            $periode = null;
        }

        $data_cuti = DataCuti::leftJoin('jenis_cutis', 'data_cutis.id_jenis_cuti', '=', 'jenis_cutis.id')
            ->leftJoin('employee_cutis', 'data_cutis.id_employee_cuti', '=', 'employee_cutis.id')
            ->leftJoin('employees', 'employee_cutis.employee_id', '=', 'employees.id')
            ->leftJoin('units', 'employees.jabatan_struktural', '=', 'units.id')
            ->select(
                'data_cutis.*',
                'employees.nama_lengkap as employee_name',
                'employees.nip_karyawan as employee_nip',
                'units.nama_unit as jabatan',
                'jenis_cutis.nama_jenis_cuti',
                'employee_cutis.tahun',
                'employee_cutis.periode'
            )
            ->whereNull('employees.deleted_at')
            ->whereMonth('data_cutis.created_at', $bulan)
            ->whereYear('data_cutis.created_at', $tahun)
            ->orderBy('data_cutis.kode_cuti', 'asc')
            ->get();

        $data = [];
        $no = 1;

        foreach ($data_cuti as $cuti) {

            /**
             * ==================================
             * TANGGAL CUTI (BERDASARKAN KODE_CUTI)
             * ==================================
             */
            $tanggal_cuti = DB::table('tanggal_cutis')
                ->where('kode_cuti', $cuti->kode_cuti)
                ->orderBy('tanggal_cuti', 'asc')
                ->pluck('tanggal_cuti')
                ->toArray();

            if (!empty($tanggal_cuti)) {
                // Kondisi 1: detail tanggal dari tabel tanggal_cutis
                $tanggal = implode(', ', array_map(function ($tgl) {
                    return Carbon::parse($tgl)->format('d-m-Y');
                }, $tanggal_cuti));
            } else {
                // Kondisi 2: range tanggal
                $tanggal = Carbon::parse($cuti->tanggal_mulai_cuti)->format('d-m-Y')
                    . ' s/d ' .
                    Carbon::parse($cuti->tanggal_selesai_cuti)->format('d-m-Y');
            }


            /**
             * ==================================
             * Lihat data pegawai CUTI (BERDASARKAN ambil cuti tahunan + kode cuti tahunan + periode)
             * ==================================
             */
            // dd($periode, $tahun, $cuti->employee_id);
            $employees_cuti = EmployeeCuti::where('tahun', $tahun)
                ->where('periode', $periode)
                ->where('employee_id', $cuti->id_employee)
                ->first();

            // dd($cuti->id_jenis_cuti);

            //keterangan cuti
            if ($cuti->id_jenis_cuti == 1) {
                $keterangan = $employees_cuti->cuti_diambil . ' Hari - ' . $cuti->jumlah_hari_cuti . ' Hari ' ;
                $sisa_cuti = $employees_cuti ? $employees_cuti->sisa_cuti : 0;
            } elseif ($cuti->id_jenis_cuti == 2) {
                $keterangan = 'Cuti Menikah 2 Hari';
                $sisa_cuti = '-';
            } elseif ($cuti->id_jenis_cuti == 3) {
                $keterangan = 'Cuti Melahirkan Maksimal 3 Bulan';
                $sisa_cuti = '-';
            } elseif ($cuti->id_jenis_cuti == 4) {
                $keterangan = 'Cuti Harian maksimal 3 Hari';
                $sisa_cuti = '-';
            } else {
                $keterangan = 'Cuti Lainnya';
                $sisa_cuti = '-';
            }

            $pengajuan_cuti = Carbon::parse($cuti->created_at)->format('d-m-Y H:i:s');

            $data[] = [
                $no++,                         // No
                $cuti->employee_nip,           // NIP
                $cuti->employee_name,          // Nama
                $cuti->jabatan,                // Jabatan
                $tanggal,                      // Tanggal Cuti
                'Periode ' . $cuti->periode . '(' . $cuti->tahun . ')',// Periode
                $cuti->jumlah_hari_cuti,            // Jumlah Hari
                $pengajuan_cuti,           // Pengajuan Cuti
                $cuti->nama_jenis_cuti,        // Jenis Cuti
                $keterangan ?? '-',      // Keterangan
                $sisa_cuti ?? '-',         // Sisa Cuti
            ];
        }

        return $data;
    }


}
