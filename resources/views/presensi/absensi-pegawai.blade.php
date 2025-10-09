@extends('includeView.layout')
@section('title', 'Show Rekap Presensi')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Absensi Presensi Pegawai (Temporary) </h4>
                    <p class="card-description">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @elseif (session('warning'))
                            <div class="alert alert-warning">
                                {{ session('warning') }}
                            </div>
                        @endif
                    </p>
                </div>
                <div class="card-body">
                    <hr>
                    <table id="dataTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Pegawai</th>
                                <th>Bagian/Departemen</th>
                                <th>Jabatan</th>
                                <th>Shift</th>
                                <th>Jam Datang</th>
                                <th>Status</th>
                                <th>Keterlambatan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($absen_datas as $absen_data)
                            <tr>
                                <td>{{ $absen_data->nama }}</td>
                                <td>{{ $absen_data->nama_departemen }}</td>
                                <td>{{ $absen_data->jabatan }}</td>
                                <td>{{ $absen_data->shift }} ({{ $absen_data->masuk }} - {{ $absen_data->keluar }})</td>
                                <td>{{ $absen_data->jam_datang }}</td>
                                <td>{{ $absen_data->status ?? '-' }}</td>
                                <td>{{ $absen_data->keterlambatan ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('presensi.verify', $absen_data->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $absen_data->id }}">
                                        <button type="submit" class="btn btn-success btn-sm">Verify</button>
                                    </form>

                                    <!-- Tombol Modal -->
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editShiftModal{{ $absen_data->id }}">  
                                        Ubah Shift
                                    </button>

                                    <!-- Modal Edit Shift -->
                                    <div class="modal fade" id="editShiftModal{{ $absen_data->id }}" tabindex="-1" aria-labelledby="editShiftModalLabel{{ $absen_data->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editShiftModalLabel{{ $absen_data->id }}">Ubah Shift dan Jam Datang</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('presensi.updateShiftPresensi', $absen_data->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group mb-3">
                                                            <label for="shift{{ $absen_data->id }}">Shift</label>
                                                            <select name="shift" id="shift{{ $absen_data->id }}" class="form-control select2" required style="width:100%">
                                                                <option value="">-- Pilih Shift --</option>
                                                                @foreach ($shifts as $shift)
                                                                    <option value="{{ $shift->nama_shift }}" 
                                                                        {{ $shift->nama_shift == $absen_data->shift ? 'selected' : '' }}>
                                                                        {{ $shift->nama_shift }} ({{ $shift->jam_masuk }} - {{ $shift->jam_pulang }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="jam_datang{{ $absen_data->id }}">Jam Datang</label>
                                                            <input 
                                                                type="time" 
                                                                name="jam_datang" 
                                                                id="jam_datang{{ $absen_data->id }}" 
                                                                class="form-control" 
                                                                step="1"
                                                                value="{{ \Carbon\Carbon::parse($absen_data->jam_datang)->format('H:i:s') }}" 
                                                                required
                                                            >
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="status{{ $absen_data->id }}">Status</label>
                                                            <select name="status" id="status{{ $absen_data->id }}" class="form-control" required>
                                                                <option value="">-- Pilih Status --</option>
                                                                @php
                                                                    $statusOptions = [
                                                                        'Tepat Waktu',
                                                                        'Terlambat Toleransi',
                                                                        'Terlambat I',
                                                                        'Terlambat II',
                                                                        'Tepat Waktu & PSW',
                                                                        'Terlambat Toleransi & PSW',
                                                                        'Terlambat I & PSW',
                                                                        'Terlambat II & PSW'
                                                                    ];
                                                                @endphp
                                                                @foreach ($statusOptions as $status)
                                                                    <option value="{{ $status }}" {{ $absen_data->status == $status ? 'selected' : '' }}>
                                                                        {{ $status }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="keterlambatan{{ $absen_data->id }}">Keterlambatan (menit)</label>
                                                            <input 
                                                                type="number" 
                                                                name="keterlambatan" 
                                                                id="keterlambatan{{ $absen_data->id }}" 
                                                                class="form-control" 
                                                                value="{{ $absen_data->keterlambatan }}" 
                                                                placeholder="Masukkan jumlah menit keterlambatan"
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pastikan jQuery dan Select2 sudah disertakan -->
                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

                    <script>
                    $(document).ready(function() {
                        // Inisialisasi select2 untuk semua modal, dengan dropdownParent agar muncul di dalam modal
                        $('.modal').each(function () {
                            let modal = $(this);
                            modal.find('.select2').select2({
                                dropdownParent: modal
                            });
                        });
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan script inisialisasi DataTable setelah halaman siap -->
@endsection