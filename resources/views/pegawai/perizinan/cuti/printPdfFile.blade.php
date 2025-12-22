<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.6;
        }

        /* ===== HEADER ===== */
        .header {
            width: 100%;
            overflow: hidden;
            border-bottom: 2px solid #1f6f5c;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .header-logo {
            float: left;
            width: 20%;
        }

        .header-logo img {
            width: 180px;
            height: auto;
        }

        .header-text {
            float: left;
            width: 80%;
            text-align: center;
        }

        .header-text .title {
            font-size: 18px;
            font-weight: bold;
            color: #1f6f5c;
        }

        .header-text .subtitle {
            font-size: 11px;
            line-height: 1.4;
        }

        .address {
            max-width: 420px;
            margin: 0 auto;
            /* word-wrap: break-word; */
        }

        /* Clear float */
        .clear {
            clear: both;
        }
        /* KODE DOKUMEN */
        .doc-code {
            margin-top: 6px;
            text-align: right;
            font-size: 11px;
        }

        .doc-code span {
            border: 1px solid #000;
            padding: 3px 10px;
            font-weight: bold;
            display: inline-block;
        }

        /* ===== JUDUL ===== */
        h4 {
            text-align: center;
            margin: 20px 0 15px;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== PARAGRAF ===== */
        p {
            margin: 6px 0;
            text-align: justify;
        }

        /* ===== TABEL DATA ===== */
        .data-table {
            width: 75%;
            margin: 10px 0 15px;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .data-table td.label {
            width: 30%;
        }

        .data-table td.colon {
            width: 3%;
            text-align: center;
        }

        /* ===== LIST TANGGAL ===== */
        ul {
            margin: 4px 0 4px 18px;
            padding: 0;
        }

        /* ===== TANDA TANGAN ===== */
        .signature-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
            text-align: center;
            table-layout: fixed; /* penting agar tidak melebar */
        }

        .signature-table td {
            width: 20%;
            padding: 4px;
            vertical-align: top;
            font-size: 11px;
        }

        .signature-box {
            height: 55px; /* diperkecil */
            margin: 4px 0;
        }

        .signature-box img {
            max-height: 55px;
            max-width: 100%;
            width: auto;
        }

        .signature-name {
            margin-top: 3px;
            font-size: 11px;
        }
    </style>
</head>
<body>

<!-- ===== HEADER ===== -->
<div class="header">

    <div class="header-logo">
        <img src="{{ $logo }}" alt="Logo" width="">
    </div>

    <div class="header-text">
        <div class="title">{{ $kops->name }}</div>

        <div class="subtitle">
            <div class="address">
                Alamat : {{ $kops->address }}   
            </div>
            Telp : {{ $kops->phone }} | Email : {{ $kops->email }} <br>
            Website : {{ $kops->website }}
        </div>
    </div>

    <div class="clear"></div>
</div>

<!-- KODE -->
<div class="doc-code">
    <span>KODE : {{ $cuti->kode_cuti }}</span>
</div>
<h4>Lembar Permohonan Cuti Pegawai</h4>

<p>Assalamu'alaikum Warohmatullahi Wabarakatuh</p>

<p>Yang bertanda tangan di bawah ini:</p>

<!-- ===== DATA PEMOHON ===== -->
<table class="data-table">
    <tr>
        <td class="label">Nama</td>
        <td class="colon">:</td>
        <td>{{ $cuti->nama_lengkap }}</td>
    </tr>
    <tr>
        <td class="label">NIP Karyawan</td>
        <td class="colon">:</td>
        <td>{{ $cuti->nip_karyawan }}</td>
    </tr>
    <tr>
        <td class="label">Unit / Jabatan</td>
        <td class="colon">:</td>
        <td>{{ $cuti->nama_unit }}</td>
    </tr>
    <tr>
        <td class="label">Alamat</td>
        <td class="colon">:</td>
        <td>{{ $cuti->alamat_lengkap }}</td>
    </tr>
    <tr>
        <td class="label">Jenis Cuti</td>
        <td class="colon">:</td>
        <td>{{ $cuti->nama_jenis_cuti }}</td>
    </tr>
    <tr>
        <td class="label">Tanggal Cuti</td>
        <td class="colon">:</td>
        <td>
            @if(!empty($tanggal_cuti))
                <ul>
                    @foreach($tanggal_cuti as $tgl)
                        <li>{{ \Carbon\Carbon::parse($tgl)->format('d-m-Y') }}</li>
                    @endforeach
                </ul>
                <strong>({{ $cuti->jumlah_hari_cuti }} Hari)</strong>
            @else
                {{ \Carbon\Carbon::parse($cuti->tanggal_mulai_cuti)->format('d-m-Y') }}
                s/d
                {{ \Carbon\Carbon::parse($cuti->tanggal_selesai_cuti)->format('d-m-Y') }}
                <br>
                <strong>({{ $cuti->jumlah_hari_cuti }} Hari)</strong>
            @endif
        </td>
    </tr>
    <tr>
        <td class="label">Keperluan Cuti</td>
        <td class="colon">:</td>
        <td>{{ $cuti->alasan_cuti }}</td>
    </tr>
    <tr>
        <td class="label">Karyawan Pengganti</td>
        <td class="colon">:</td>
        <td>{{ $cuti->karyawan_pengganti }}</td>
    </tr>

    @if($cuti->id_jenis_cuti == 1)
    <tr>
        <td class="label">Keterangan</td>
        <td class="colon">:</td>
        <td>
            Jumlah Cuti: {{ $cuti->jumlah_cuti }} Hari ({{ $cuti->tahun }}/Periode {{ $cuti->periode }})<br>
            Digunakan: {{ $cuti->cuti_diambil }} Hari<br>
            Sisa: {{ $cuti->sisa_cuti }} Hari
        </td>
    </tr>
    @endif
</table>

<p>
    Demikian permohonan ini saya sampaikan. Atas perhatian Bapak/Ibu, saya ucapkan terima kasih.
</p>

<p>Wassalamu’alaikum Warohmatullahi Wabarakatuh.</p>

<!-- ===== TANDA TANGAN ===== -->
<table class="signature-table">
    <tr>
        <td>Mengetahui<br>Ka. Subbag SDI</td>
        <td>Menyetujui<br>Atasan Langsung</td>
        <td>Karyawan Pengganti</td>
        <td>
            Nganjuk, {{ \Carbon\Carbon::parse($cuti->created_at)->format('d-m-Y') }}<br>
            Pemohon
        </td>
        <td>Pencatat</td>
    </tr>

    <tr>
        <td class="signature-box">
            @if(isset($cuti->ttd_mengetahui))
                <img src="{{ $mengetahui }}" alt="ttd mengetahui">
            @endif
        </td>
        <td class="signature-box">
            @if(isset($cuti->ttd_menyetujui))
                <img src="{{ $menyetujui }}" alt="ttd menyetujui">
            @endif
        </td>
        <td class="signature-box">
            @if(isset($cuti->ttd_karyawan_pengganti))
                <img src="{{ $pengganti }}" alt="ttd pengganti">
            @endif
        </td>
        <td class="signature-box">
            @if(isset($cuti->ttd_karyawan_pemohon))
                <img src="{{ $pemohon }}" alt="ttd pemohon">
            @endif
        </td>
        <td class="signature-box">
            @if(isset($cuti->ttd_pencatat))
                <img src="{{ $pencatat }}" alt="ttd pencatat">
            @endif
        </td>
    </tr>

    <tr>
        <td class="signature-name">
            ({{ $cuti->mengetahui ?? '....................' }})
        </td>
        <td class="signature-name">
            ({{ $cuti->menyetujui ?? '....................' }})
        </td>
        <td class="signature-name">
            ({{ $cuti->karyawan_pengganti ?? '....................' }})
        </td>
        <td class="signature-name">
            ({{ $cuti->nama_lengkap ?? '....................' }})
        </td>
        <td class="signature-name">
            ({{ $cuti->pencatat ?? '....................' }})
        </td>
    </tr>
</table>

</body>
</html>