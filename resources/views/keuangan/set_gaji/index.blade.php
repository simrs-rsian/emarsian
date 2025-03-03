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
