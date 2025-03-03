<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji Karyawan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .container { width: 100%; }
        .header { text-align: center; }
        .header img { width: 150px; }
        .title { font-size: 16px; font-weight: bold; }
        .info-table, .salary-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .info-table td { padding: 5px; }
        .salary-table td, .salary-table th { border: 1px solid black; padding: 5px; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .italic { font-style: italic; }
        .footer { text-align: right; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $logo }}" alt="Logo" />
            <br>
            <div class="title"><br><u>SLIP GAJI KARYAWAN</u></div>
        </div>
        <table class="info-table">
            <tr>
                <td>Periode Gaji Tahun: <b>{{ $slip_penggajian->tahun }}</b></td>
            </tr>
            <tr>
                @php
                    $bulan = '';
                    switch ($slip_penggajian->bulan) {
                        case 1:
                            $bulan = 'Januari';
                            break;
                        case 2:
                            $bulan = 'Februari';
                            break;
                        case 3:
                            $bulan = 'Maret';
                            break;
                        case 4:
                            $bulan = 'April';
                            break;
                        case 5:
                            $bulan = 'Mei';
                            break;
                        case 6:
                            $bulan = 'Juni';
                            break;
                        case 7:
                            $bulan = 'Juli';
                            break;
                        case 8:
                            $bulan = 'Agustus';
                            break;
                        case 9:
                            $bulan = 'September';
                            break;
                        case 10:
                            $bulan = 'Oktober';
                            break;
                        case 11:
                            $bulan = 'November';
                            break;
                        case 12:
                            $bulan = 'Desember';
                            break;
                    }
                @endphp
                <td>Bulan: <b>{{ $bulan }}</b></td>
            </tr>
        </table>
        <table class="info-table">
            <tr>
                <td>Nomor Referensi</td>
                <td>: <b>K.{{ $slip_penggajian->id }}</b></td>
                <td>Nama Pegawai</td>
                <td>: <b>{{ $employee->nama_lengkap }}</b></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: <b>{{ $employee->unit->nama_unit }}</b></td>
                <td>Karyawan</td>
                <td>: <b>{{ $employee->statusKaryawan->nama_status }}</b></td>
            </tr>
        </table>
        <table class="salary-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Nama Gaji/Potongan</th>
                <th class="right">Nominal</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rincianslipgaji as $rincian_gaji)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rincian_gaji->nama_gaji }}</td>
                <td class="right">Rp. {{ number_format($rincian_gaji->nominal_gaji, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="bold">
                <td colspan="2" class="italic">Sub Total Gaji</td>
                <td class="right">Rp. {{ number_format($slip_penggajian->total_gaji, 0, ',', '.') }}</td>
            </tr>
            @foreach($rincianslippotongan as $rincian_potongan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rincian_potongan->nama_potongan }}</td>
                <td class="right">Rp. {{ number_format($rincian_potongan->nominal_potongan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="bold">
                <td colspan="2" class="italic">Sub Total Potongan</td>
                <td class="right">Rp. {{ number_format($slip_penggajian->total_potongan, 0, ',', '.') }}</td>
            </tr>
            <tr class="bold">
                <td colspan="2" class="italic">Total Terima</td>
                <td class="right">Rp. {{ number_format($slip_penggajian->total_terima, 0, ',', '.') }}</td>
            </tr>
            </tbody>
        </table>
        <div class="footer">
            Dibuat oleh,<n><br>
            <b>Admin Keuangan</b>
        </div>
    </div>
</body>
</html>
