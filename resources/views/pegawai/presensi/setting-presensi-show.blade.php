@extends('includeView.layout')
@section('title', 'Show Rekap Presensi')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Setting Jadwal Presensi Pegawai </h4>
                    <p class="card-description">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </p>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr></tr>
                            <td><strong>Nama Pegawai</strong></td>
                            <td>:</td>
                            <td>{{ $pegawai->nama }}</td>
                        </tr>
                        <tr>
                            <td><strong>Bagian/Departemen</strong></td>
                            <td>:</td>
                            <td>{{ $pegawai->nama_departemen }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jabatan</strong></td>
                            <td>:</td>
                            <td>{{ $pegawai->jbtn }}</td>
                        </tr>
                    </table>
                    <a href="{{ route('pegawai.setting_presensi', ['id' => $pegawai->id]) }}" class="btn btn-danger mb-3">Kembali</a>
                    <hr>
                    <h4 class="card-title">Filter</h4>
                    <form method="GET" action="{{ route('pegawai.setPresensi.show' , ['id' => $pegawai->id]) }}" id="filterForm"> 
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="month">Bulan:</label>
                                        @php
                                            $currentMonth = date('m'); // bulan sekarang
                                        @endphp
                                        <select name="month" class="form-control" id="month">
                                            <option value="">-- Pilih Bulan --</option>
                                            @foreach (range(1, 12) as $m)
                                                <option value="{{ $m }}" 
                                                    {{ (int)request('month', $currentMonth) === $m ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="years">Tahun:</label>
                                        <select name="years" class="form-control" id="years">
                                            <option value="">-- Pilih Tahun --</option>
                                            @php
                                                $currentYear = date('Y'); // tahun sekarang
                                                $startYear = $currentYear - 5;
                                                $endYear = $currentYear + 5;
                                            @endphp
                                            @for ($y = $startYear; $y <= $endYear; $y++)
                                                <option value="{{ $y }}" 
                                                    {{ (int)request('years', $currentYear) === $y ? 'selected' : '' }}>
                                                    {{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary me-2" id="submitButton">Submit</button>
                                    <button type="button" class="btn btn-light-secondary" id="resetButton">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered" id="rekapPresensiTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Hari, Tanggal</th>
                                    <th>Shift</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @php
                                \Carbon\Carbon::setLocale('id'); // set locale Indo
                                $startDate = \Carbon\Carbon::createFromDate($year, $month, 1);
                                $endDate = $startDate->copy()->endOfMonth();
                                $no = 1;
                            @endphp

                            <tbody>
                                @for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                                    @php
                                        $dayNumber = $date->day;
                                        $column = 'h' . $dayNumber;
                                        $currentShift = $jadwal->first()->$column ?? '';
                                    @endphp
                                    <tr class="{{ $date->isSunday() ? 'row-libur' : '' }}">
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $date->translatedFormat('l, d F Y') }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('pegawai.update_presensi', [$pegawai->id]) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="tahun" value="{{ $year }}">
                                                <input type="hidden" name="bulan" value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
                                                <input type="hidden" name="hari" value="{{ $dayNumber }}">
                                                <select name="shift" class="form-control form-control-sm select2-shift">
                                                    <option value="">-- Pilih Shift --</option>
                                                    <option value="LIBUR"> LIBUR </option>
                                                    @foreach ($sift as $s)
                                                        <option value="{{ $s->shift }}" {{ $currentShift == $s->shift ? 'selected' : '' }}>
                                                            {{ $s->shift }} ({{ $s->jam_masuk }} - {{ $s->jam_pulang }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                        </td>
                                        <td>
                                                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan script inisialisasi DataTable setelah halaman siap -->
@endsection