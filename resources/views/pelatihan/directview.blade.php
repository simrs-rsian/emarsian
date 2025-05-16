@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="">Data Peserta {{ $pelatihans->nama_pelatihan }}</h2><br>
                    <p class="card-description">
                    </p>
                    
                    <div class="col-md-12">
                        <table class="table table-dark">
                            <tr>
                                <td>Rincian Pelatihan</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Tanggal Mulai/Selesai</td>
                                <td>:</td>
                                <td>{{ $pelatihans->tanggal_mulai }}/ {{ $pelatihans->tanggal_selesai }}</td>
                            </tr>
                            <tr>
                                <td>Penyelenggara</td>
                                <td>:</td>
                                <td>{{ $pelatihans->penyelenggara }}</td>
                            </tr>
                            <tr>
                                <td>Tempat Lokasi</td>
                                <td>:</td>
                                <td>{{ $pelatihans->lokasi }}</td>
                            </tr>
                            <tr>
                                <td>Jenis Pelatihan</td>
                                <td>:</td>
                                <td>{{ $pelatihans->nama_jenis }}</td>
                            </tr>
                        </table>
                    </div>                    
                    <button type="button" class="btn btn-md btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createPesertaPelatihanModal">
                        TAMBAH PESERTA
                    </button>
                    <div class="table-responsive pt-3">
                        
                        <table id="dataTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIP Pegawai</th>
                                    <th>Nama peserta</th>
                                    <th>Poin/Jam</th>
                                    <th>Jabatan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesertaPelatihans as $key => $peserta)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $peserta->nip_karyawan }}</td>
                                        <td>{{ $peserta->nama_lengkap }}</td>
                                        <td>{{ $peserta->poin }}</td>
                                        <td>{{ $peserta->nama_unit }}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPesertaPelatihanModal{{ $peserta->id_riwayat }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('riwayat_pelatihan.destroy', $peserta->id_riwayat) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editPesertaPelatihanModal{{ $peserta->id_riwayat }}" tabindex="-1" aria-labelledby="editPesertaPelatihanModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editPesertaPelatihanModalLabel">Ganti Peserta Pelatihan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('riwayat_pelatihan.update', $peserta->id_riwayat) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            
                                                            <input type="hidden" name="id_pelatihan" value="{{ $peserta->id_pelatihan }}">
                                                            <label for="nama_pelatihan{{ $peserta->id }}" class="form-label">Nama Pegawai</label>
                                                            <select name="id_employee" class="form-control" style="width:100%" required>
                                                                <option value="">Pilih Pegawai</option>
                                                                @foreach($employees as $employee)
                                                                    <option value="{{ $employee->id }}" {{ $employee->id == $peserta->id ? 'selected' : '' }} >{{ $employee->nama_lengkap }}</option>
                                                                @endforeach
                                                            </select>
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

<div class="modal fade" id="createPesertaPelatihanModal" tabindex="-1" aria-labelledby="createPesertaPelatihanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPesertaPelatihanModalLabel">Tambah Data Peserta {{ $pelatihans->nama_pelatihan }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pelatihan.directstore') }}" method="POST" id="unitForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Peserta</label>
                        <input type="hidden" value="{{ $pelatihans->id }}" name="id_pelatihan">
                        <select name="id_employee[]" class="form-control pesertaMultiple" multiple="multiple" style="width:100%" required>
                            <option value="">Pilih Pegawai</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
