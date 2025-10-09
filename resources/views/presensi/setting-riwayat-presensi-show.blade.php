@extends('includeView.layout')
@section('title', 'Show Rekap Presensi')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Riwayat Setting Presensi Pegawai </h4>
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
                    <a href="{{ route('presensi.index') }}" class="btn btn-danger mb-3">Kembali</a>   
                    <hr>
                    <h4 class="card-title">Filter</h4>
                    <form method="GET" action="{{ route('presensi.setRiwayatPresensi' , ['id' => $pegawai->id]) }}" id="filterForm"> 
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
                    <!-- Tombol Tambah -->
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahRiwayat">
                        Tambah Riwayat Presensi
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="modalTambahRiwayat" tabindex="-1" aria-labelledby="modalTambahRiwayatLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="modalTambahRiwayatLabel">Tambah Riwayat Presensi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <form action="{{ route('presensi.setRiwayatPresensi.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $pegawai->id }}">

                                        <div class="mb-3">
                                            <label for="tanggal_tambah" class="form-label">Tanggal</label>
                                            <input type="date" id="tanggal_tambah" name="tanggal" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="jam_datang_tambah" class="form-label">Jam Datang</label>
                                            <input type="time" id="jam_datang_tambah" name="jam_datang" class="form-control" step="1" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="jam_pulang_tambah" class="form-label">Jam Pulang</label>
                                            <input type="time" id="jam_pulang_tambah" name="jam_pulang" class="form-control" step="1">
                                        </div>

                                        <div class="mb-3">
                                            <label for="keterangan_tambah" class="form-label">Keterangan</label>
                                            <textarea id="keterangan_tambah" name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="mdi mdi-content-save"></i> Simpan Riwayat
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                    <th>No</th>
                                    <th>Hari, Tanggal</th>
                                    <th>Shift</th>
                                    <th>Jam Datang</th>
                                    <th>Jam Pulang</th>
                                    <th>Status</th>
                                    <th>Keterlambatan</th>
                                    <th>Durasi Kerja</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datesInMonth as $date)
                                    @php
                                        $tanggal = $date->toDateString();
                                        $item = $dataByDate[$tanggal] ?? null;
                                        $isLibur = !$item;

                                        // pastikan id unik, ganti karakter aneh dari jam_datang
                                        $uniqueId = isset($item->jam_datang)
                                            ? preg_replace('/[^A-Za-z0-9]/', '', $item->id . $item->jam_datang)
                                            : $item->id ?? uniqid();
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
                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                        <td>
                                            @if (!$isLibur && isset($item->id))
                                                <!-- Tombol Ubah -->
                                                <button type="button" class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ubahRekapPresensiModal{{ $uniqueId }}">
                                                    <i class="mdi mdi-pencil"></i> Ubah
                                                </button>

                                                <!-- Modal Ubah Rekap Presensi -->
                                                <div class="modal fade" id="ubahRekapPresensiModal{{ $uniqueId }}" tabindex="-1"
                                                    aria-labelledby="ubahRekapPresensiModalLabel{{ $uniqueId }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="ubahRekapPresensiModalLabel{{ $uniqueId }}">
                                                                    Ubah Rekap Presensi ({{ $date->format('d-m-Y') }})
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form action="{{ route('presensi.setRiwayatPresensi.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')

                                                                    <!-- ID -->
                                                                    <input type="hidden" name="id" value="{{ $item->id }}">

                                                                    <!-- TANGGAL & JAM_DATANG ID -->
                                                                    <input type="hidden" name="jam_datang_id" value="{{ $item->jam_datang }}">
                                                                    <input type="hidden" name="date" value="{{ \Carbon\Carbon::parse($item->jam_datang)->format('Y-m-d') }}">

                                                                    <!-- JAM DATANG -->
                                                                    <div class="mb-3">
                                                                        <label for="jam_datang_{{ $uniqueId }}" class="form-label">Jam Datang</label>
                                                                        <input type="time" id="jam_datang_{{ $uniqueId }}" name="jam_datang"
                                                                            value="{{ $item->jam_datang ? \Carbon\Carbon::parse($item->jam_datang)->format('H:i:s') : '' }}"
                                                                            step="1" class="form-control" required>
                                                                    </div>

                                                                    <!-- JAM PULANG -->
                                                                    <div class="mb-3">
                                                                        <label for="jam_pulang_{{ $uniqueId }}" class="form-label">Jam Pulang</label>
                                                                        <input type="time" id="jam_pulang_{{ $uniqueId }}" name="jam_pulang"
                                                                            value="{{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i:s') : '' }}"
                                                                            step="1" class="form-control">
                                                                    </div>
                                                                    
                                                                    <!-- KETERANGAN -->
                                                                    <div class="mb-3">
                                                                        <label for="keterangan_{{ $uniqueId }}" class="form-label">Keterangan</label>
                                                                        <textarea id="keterangan_{{ $uniqueId }}" name="keterangan"
                                                                                class="form-control" rows="3"
                                                                                placeholder="Masukkan catatan tambahan...">{{ $item->keterangan ?? '' }}</textarea>
                                                                    </div>

                                                                    <!-- SUBMIT -->
                                                                    <button type="submit" class="btn btn-success w-100">
                                                                        <i class="mdi mdi-content-save"></i> Simpan Perubahan
                                                                    </button>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            @else
                                                <span class="text-muted">-</span>
                                            @endif

                                            <!-- Hapus Jam Pulang (jika hari ini) -->
                                            @if (!$isLibur && isset($item->jam_datang) && \Carbon\Carbon::parse($item->jam_datang)->isToday())
                                                <form action="{{ route('presensi.setRiwayatPresensi.hapusJamPulang') }}" method="POST"
                                                    class="mt-2">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <input type="hidden" name="jam_datang" value="{{ $item->jam_datang }}">
                                                    <button type="submit" class="btn btn-sm btn-danger w-100">
                                                        <i class="mdi mdi-delete"></i> Hapus Jam Pulang
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

@endsection