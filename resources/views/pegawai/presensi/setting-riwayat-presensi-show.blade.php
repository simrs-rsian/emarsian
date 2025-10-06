@extends('includeView.layout')
@section('title', 'Show Rekap Presensi')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Setting Presensi Pegawai </h4>
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
                    <form method="GET" action="{{ route('pegawai.setRiwayatPresensi.show' , ['id' => $pegawai->id]) }}" id="filterForm"> 
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
                        @php
                            use Carbon\Carbon;
                            use Carbon\CarbonPeriod;

                            Carbon::setLocale('id');

                            $month = Carbon::parse($data->first()->jam_datang ?? now())->month;
                            $year = Carbon::parse($data->first()->jam_datang ?? now())->year;

                            $datesInMonth = CarbonPeriod::create(
                                "$year-$month-01",
                                Carbon::create($year, $month)->endOfMonth()
                            );

                            $dataByDate = [];
                            foreach ($data as $item) {
                                $tanggal = Carbon::parse($item->jam_datang)->toDateString();
                                $dataByDate[$tanggal] = $item;
                            }

                            $no = 1;
                        @endphp

                        <table class="table table-bordered table-striped table-hover align-middle" id="rekapPresensiTable">
                            <thead class="table-dark">
                                <tr class="text-center">
                                    <th style="width: 50px;">No</th>
                                    <th style="width: 180px;">Hari, Tanggal</th>
                                    <th style="width: 200px;">Shift</th>
                                    <th style="width: 120px;">Jam Datang</th>
                                    <th style="width: 120px;">Jam Pulang</th>
                                    <th>Status</th>
                                    <th>Keterlambatan</th>
                                    <th>Durasi Kerja</th>
                                    <th style="width: 550px;">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datesInMonth as $date)
                                    @php
                                        $tanggal = $date->toDateString();
                                        $item = $dataByDate[$tanggal] ?? null;
                                        $isLibur = !$item;
                                    @endphp
                                    <tr class="{{ $isLibur ? 'table-danger text-danger' : '' }}">
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $date->translatedFormat('l') }}, {{ $date->format('d-m-Y') }}</td>
                                        <td>
                                            {{ $item->shift ?? '-' }} 
                                            ({{ $item->jam_masuk ?? '-' }} - {{ $item->jam_keluar ?? '-' }})
                                        </td>
                                        <td>{{ $item->jam_datang ?? '-' }}</td>
                                        <td>{{ $item->jam_pulang ?? '-' }}</td>
                                        <td>{{ $item->status ?? 'Libur / Record Tidak Ditemukan' }}</td>
                                        <td>{{ $item->keterlambatan ?? '-' }}</td>
                                        <td>{{ $item->durasi ?? '-' }}</td>
                                        <td>
                                            @if ($isLibur)
                                                <span class="text-muted">-</span>
                                            @else
                                            <form action="{{ route('pegawai.setRiwayatPresensi.update') }}" method="POST" class="d-flex flex-column">
                                                @csrf
                                                <input type="hidden" name="jam_datang" value="{{ $item->jam_datang }}">
                                                <input type="hidden" name="id_presensi" value="{{ $item->id ?? '' }}">
                                                <textarea name="catatan" class="form-control mb-2" rows="3" placeholder="Catatan">{{ $item->keterangan ?? '' }}</textarea>
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="mdi mdi-content-save"></i> Simpan
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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