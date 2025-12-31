@extends('includeView.layout')
@section('content')
{{-- Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Setting Data Cuti Pegawai</h4>
                    <p class="card-description">
                        <!-- Modal for importing data -->
                        <button type="button" class="btn btn-sm btn-success mb-3" data-bs-toggle="modal" data-bs-target="#importDataModal">
                            Import Data Cuti Pegawai
                        </button>

                        <!-- modal tambah data manual -->
                        <button type="button" class="btn btn-sm btn-info mb-3" data-bs-toggle="modal" data-bs-target="#createCutiModal">
                            Tambah Data Cuti Pegawai
                        </button>

                        <!-- modal export data pilihan perbulan per tahun -->
                        <button type="button" class="btn btn-sm btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#exportDataModal">
                            Export Data Cuti Pegawai
                        </button>

                        <div class="modal fade" id="importDataModal" tabindex="-1" role="dialog" aria-labelledby="importExcelLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importExcelLabel">Import Data</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <a href="{{ route('perizinan.cuti.exportEmployeeCuti') }}" class="btn btn-success btn-sm">Download Excel Format</a>
                                    </div>
                                    <div class="modal-body">
                                        <h5>Panduan <br>(Mohon dibaca terlebih dahulu sebelum import data): </h5>
                                        <ol>
                                            <li>Silahkan Upload file dengan extention .xlsx dengan Format data yang sudah di sediakan diatas dengan cara Download terlebih dahulu</li>
                                            <li style="color: red;">Warning: Setelah file yang di download dibuka mohon jangan ubah apapun didalam format tersebut, Silahkan isi Nominal yang sesuai kebutuhan dengan baik dan benar.</li>
                                            <li>Dalam proses import data, sistem akan memproses data tersebut dan memasukkan data ke dalam database, mungkin akan membutuhkan Waktu yang cukup lama (tergantung banyaknya data), mohon ditunggu sampai muncul notifikasi berikut "Data Setting Cuti Pegawai berhasil diimport".</li>
                                            <li>Jika dirasa ada yang kurang atau butuh penyesuaian dengan nama menunya silahkan hubungi Tim IT.</li>
                                        </ol>
                                    </div>
                                    <form action="{{ route('perizinan.cuti.importEmployeeCuti') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="file">Select Excel File</label>
                                                <input type="file" name="file" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Import</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="createCutiModal" tabindex="-1" aria-labelledby="createCutiModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="createCutiModalLabel">Tambah Data Cuti Pegawai</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('perizinan.cuti.pegawai.store') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="employee_id" class="form-label">Karyawan</label>
                                                <select name="employee_id"
                                                        id="employee_id"
                                                        class="form-control select2-employee"
                                                        required>
                                                    <option value="">Pilih Karyawan</option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}">
                                                            {{ $employee->nama_lengkap }} ({{ $employee->nip_karyawan }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tahun" class="form-label">Tahun</label>
                                                <select name="tahun" id="tahun" class="form-control">
                                                    @for ($year = now()->year - 5; $year <= now()->year + 1; $year++)
                                                        <option value="{{ $year }}"
                                                            {{ request('tahun', now()->year) == $year ? 'selected' : '' }}>
                                                            {{ $year }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="periode" class="form-label">Periode</label>
                                                <select name="periode" id="periode" class="form-control" required>
                                                    <option value="1">Periode 1 (Januari - Juni)</option>
                                                    <option value="2">Periode 2 (Juli - Desember)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="jumlah_cuti" class="form-label">Jumlah Cuti (Hari)</label>
                                                <input type="number" name="jumlah_cuti" id="jumlah_cuti" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="cuti_diambil" class="form-label">Cuti Yang diambil (Hari)</label>
                                                <input type="number" name="cuti_diambil" id="cuti_diambil" class="form-control">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                        <div class="modal fade" id="exportDataModal" tabindex="-1" aria-labelledby="exportDataModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exportDataModalLabel">Export Data Cuti Pegawai</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('perizinan.cuti.exportEmployeeCutiFiltered') }}" method="GET">
                                            <div class="mb-3">
                                                <label for="tahun" class="form-label">Tahun</label>
                                                <select name="tahun" id="tahun" class="form-control">
                                                    @for ($year = now()->year - 5; $year <= now()->year + 1; $year++)
                                                        <option value="{{ $year }}"
                                                            {{ request('tahun', now()->year) == $year ? 'selected' : '' }}>
                                                            {{ $year }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="bulan" class="form-label">Bulan</label>
                                                <select name="bulan" id="bulan" class="form-control" required>
                                                    <option value="1" {{ now()->month == 1 ? 'selected' : '' }}>Januari</option>
                                                    <option value="2" {{ now()->month == 2 ? 'selected' : '' }}>Februari</option>
                                                    <option value="3" {{ now()->month == 3 ? 'selected' : '' }}>Maret</option>
                                                    <option value="4" {{ now()->month == 4 ? 'selected' : '' }}>April</option>
                                                    <option value="5" {{ now()->month == 5 ? 'selected' : '' }}>Mei</option>
                                                    <option value="6" {{ now()->month == 6 ? 'selected' : '' }}>Juni</option>
                                                    <option value="7" {{ now()->month == 7 ? 'selected' : '' }}>Juli</option>
                                                    <option value="8" {{ now()->month == 8 ? 'selected' : '' }}>Agustus</option>
                                                    <option value="9" {{ now()->month == 9 ? 'selected' : '' }}>September</option>
                                                    <option value="10" {{ now()->month == 10 ? 'selected' : '' }}>Oktober</option>
                                                    <option value="11" {{ now()->month == 11 ? 'selected' : '' }}>November</option>
                                                    <option value="12" {{ now()->month == 12 ? 'selected' : '' }}>Desember</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Export</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    </p>
                    @php
                        $currentYear  = now()->year;
                        $currentMonth = now()->month;
                        $defaultPeriode = $currentMonth <= 6 ? 1 : 2;
                    @endphp

                    <form action="{{ route('perizinan.cuti.pegawai.index') }}" method="GET">
                        <div class="row">
                            {{-- Tahun --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tahun">Tahun</label>
                                    <select name="tahun" id="tahun" class="form-control">
                                        @for ($year = $currentYear - 5; $year <= $currentYear + 1; $year++)
                                            <option value="{{ $year }}"
                                                {{ request('tahun', $currentYear) == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            {{-- Periode --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="periode">Periode</label>
                                    <select name="periode" id="periode" class="form-control">
                                        <option value="1"
                                            {{ request('periode', $defaultPeriode) == 1 ? 'selected' : '' }}>
                                            Periode 1 (Januari - Juni)
                                        </option>
                                        <option value="2"
                                            {{ request('periode', $defaultPeriode) == 2 ? 'selected' : '' }}>
                                            Periode 2 (Juli - Desember)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Button --}}
                            <div class="col-md-3 align-self-end">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('perizinan.cuti.pegawai.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive pt-3">
                        <table id="dataTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 200px;">Action</th>
                                    <th>NIP Karyawan</th>
                                    <th>Nama Lengkap</th>
                                    <th>Tahun (Periode)</th>
                                    <th>Total Cuti</th>
                                    <th>Cuti Diambil</th>
                                    <th>Sisa Cuti</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cuti_datas as $key => $data)
                                    <tr>
                                        <td style="width: 200px;">
                                            <a href="{{ route('perizinan.riwayat.cuti.index', ['employee_id' => $data->employee_id]) }}" 
                                            class="btn btn-info btn-sm">
                                                Riwayat Cuti
                                            </a>

                                            <a href="{{ route('perizinan.cuti.pegawai.edit', [
                                                $data->employee_id,
                                                'tahun' => $data->tahun,
                                                'periode' => $data->periode
                                            ]) }}" class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                        </td>
                                        <td>{{ $data->employee_nip }}</td>
                                        <td>{{ $data->employee_name }}</td>
                                        <td>{{ $data->tahun ?? '0000' }} (Periode {{ $data->periode ?? '0' }})</td>
                                        <td>{{ $data->jumlah_cuti ?? '0' }} Hari</td>
                                        <td>{{ $data->cuti_diambil ?? '0' }} Hari</td>
                                        <td>{{ $data->sisa_cuti ?? '0' }} Hari</td>
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
<script>
    $(document).ready(function () {
        $('#createCutiModal').on('shown.bs.modal', function () {
            $('.select2-employee').select2({
                dropdownParent: $('#createCutiModal'),
                width: '100%',
                placeholder: 'Pilih Karyawan',
                allowClear: true
            });
        });
    });
</script>


@endsection
