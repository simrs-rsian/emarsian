<?php

namespace App\Exports;

use App\Models\Employee\Employee;
use App\Models\Keuangan\DefaultGaji;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeDefaultGajiExport implements WithHeadings, FromArray
{
    public function headings(): array
    {
        // Ambil daftar default gaji
        $gajiList = DefaultGaji::orderBy('mode_id')->get(['id', 'gaji_nama'])->toArray();
        
        // Heading awal
        $headings = ['ID', 'NIP', 'Nama'];
        $subHeadings = ['ID', 'NIP', 'Nama'];

        // Tambahkan judul gaji nama dan id
        foreach ($gajiList as $gaji) {
            $headings[] = $gaji['id'];
            $subHeadings[] = $gaji['gaji_nama'];
        }

        return [$headings, $subHeadings];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $event->sheet->getDelegate()->getRowDimension(1)->setVisible(false);
            },
        ];
    }

    public function array(): array
    {
        $employees = Employee::orderBy('nama_lengkap')->get();
        $gajiList = DefaultGaji::orderBy('id')->pluck('gaji_nama')->toArray();

        $data = [];
        foreach ($employees as $employee) {
            // Data awal: ID dan Nama
            $row = [
                $employee->id,
                $employee->nip_karyawan,
                $employee->nama_lengkap
            ];

            // Tambahkan kolom gaji dengan nilai default 0
            foreach ($gajiList as $gaji) {
                $row[] = 0; // Atau nilai lain sesuai kebutuhan
            }

            $data[] = $row;
        }

        return $data;
    }
}
