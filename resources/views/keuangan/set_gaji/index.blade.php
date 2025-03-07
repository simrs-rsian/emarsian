@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Setting Penggajian</h4>
                    <p class="card-description">

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </p>
                    <div class="table-responsive pt-3">
                        <!-- Modal for importing data -->
                        <button type="button" class="btn btn-sm btn-success mb-3" data-bs-toggle="modal" data-bs-target="#importDataModal">
                            Import DATA
                        </button>                   
                    </div>
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
                                    <a href="{{ route('setting_gaji.exportEmployeeGaji') }}" class="btn btn-success btn-sm">Download Excel Format</a>
                                </div>
                                <div class="modal-body">
                                    <h5>Panduan <br>(Mohon dibaca terlebih dahulu sebelum import data): </h5>
                                    <ol>
                                        <li>Silahkan Upload file dengan extention .xlsx dengan Format data yang sudah di sediakan diatas dengan cara Download terlebih dahulu</li>
                                        <li style="color: red;">Warning: Setelah file yang di download dibuka mohon jangan ubah apapun didalam format tersebut, Silahkan isi Nominal yang sesuai kebutuhan dengan baik dan benar.</li>
                                        <li>Dalam proses import data, sistem akan memproses data tersebut dan memasukkan data ke dalam database, mungkin akan membutuhkan Waktu yang cukup lama (tergantung banyaknya data), mohon ditunggu sampai muncul notifikasi berikut "Data gaji dan potongan berhasil diimport".</li>
                                        <li>Jika dirasa ada yang kurang atau butuh penyesuaian dengan nama menunya silahkan hubungi Tim IT.</li>
                                    </ol>
                                </div>
                                <form action="{{ route('setting_gaji.importEmployeeGaji') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="table-responsive pt-3">
                        <table id="employeesTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIP Karyawan</th>
                                    <th>Nama Lengkap</th>
                                    <th>Status Karyawan</th>
                                    <th>Jabatan</th>
                                    <th>Golongan</th>
                                    <th style="width: 200px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $key => $employee)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $employee->nip_karyawan }}</td>
                                        <td>{{ $employee->nama_lengkap }}</td>
                                        <td>{{ $employee->namastatuskar ?? '-' }}</td>
                                        <td>{{ $employee->nama_unit ?? '-' }}</td>
                                        <td>{{ $employee->nama_golongan ?? '-' }}</td>
                                        <td style="width: 200px;">
                                            <a href="{{ route('setting_gaji.show', $employee->id) }}" class="btn btn-danger btn-sm">Setting Gaji</a>
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
