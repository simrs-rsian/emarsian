@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Employees</h4>
                    <p class="card-description">
                        <a href="{{ route('employee.create') }}" class="btn btn-primary btn-md">Tambah Pegawai</a>
                        <!-- <a href="{{ route('employee.viewImport') }}" class="btn btn-success mb-3">Import Data Pegawai</a> -->

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
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>TMT</th>
                                    <th>TMTA</th>
                                    <th>Masa Kerja</th>
                                    <th>Pendidikan</th>
                                    <th>Jurusan</th>
                                    <th>Profesi</th>
                                    <th>Status Karyawan</th>
                                    <th>Status Keluarga</th>
                                    <th>Jabatan Struktural</th>
                                    <th>Golongan</th>
                                    <th>Alamat Lengkap</th>
                                    <th>Telepon</th>
                                    <th>Photo</th>
                                    <th>Kelompok Usia</th>
                                    <th>Umur</th>
                                    <th style="width: 200px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $key => $employee)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $employee->nip_karyawan }}</td>
                                        <td>{{ $employee->nama_lengkap }}</td>
                                        <td>{{ $employee->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ $employee->tempat_lahir }}</td>
                                        <td>{{ $employee->tanggal_lahir }}</td>
                                        <td>{{ $employee->tmt }}</td>
                                        <td>{{ $employee->tmta }}</td>
                                        <td>{{ $employee->masa_kerja }}</td>
                                        <td>{{ $employee->nama_pendidikan ?? '-' }}</td>
                                        <td>{{ $employee->jurusan }}</td>
                                        <td>{{ $employee->nama_profesi ?? '-' }}</td>
                                        <td>{{ $employee->namastatuskar ?? '-' }}</td>
                                        <td>{{ $employee->namastatuskel ?? '-' }}</td>
                                        <td>{{ $employee->nama_unit ?? '-' }}</td>
                                        <td>{{ $employee->nama_golongan ?? '-' }}</td>
                                        <td>{{ $employee->alamat_lengkap }}</td>
                                        <td>{{ $employee->telepon ?? '-' }}</td>
                                        <td>
                                            @if($employee->photo)
                                                <img src="{{ url($employee->photo) }}" alt="Photo" width="50">
                                            @else
                                                Tidak ada photo
                                            @endif
                                        </td>
                                        <td>{{ $employee->nama_kelompok }}</td>
                                        <td>{{ $employee->umur }} Tahun</td>
                                        <td style="width: 200px;">
                                            <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#ubahPassword{{ $employee->id }}">
                                                Ubah Password
                                            </button>
                                            <a href="{{ route('employee.show', $employee->id) }}" class="btn btn-info btn-sm">Detail</a>
                                            <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('employee.destroy', $employee->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="ubahPassword{{ $employee->id }}" tabindex="-1" aria-labelledby="ubahPasswordLabel{{ $employee->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ubahPasswordLabel{{ $employee->id }}">Ubah Password Pegawai</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('employee.update_password', $employee->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="{{ $employee->id }}">

                                                        <div class="mb-3">
                                                            <label for="passnew{{ $employee->id }}" class="form-label">Password Baru</label>
                                                            <input type="password" class="form-control" id="passnew{{ $employee->id }}" name="passnew" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="passconf{{ $employee->id }}" class="form-label">Konfirmasi Password Baru</label>
                                                            <input type="password" class="form-control" id="passconf{{ $employee->id }}" name="passconf" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
