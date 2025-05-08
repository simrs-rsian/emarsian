@extends('includeView.layout')
@section('content')

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit Profil Karyawan</h4>
            <form action="{{ route('pegawai.updateProfile', $employee->id) }}" method="GET" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Foto -->
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <div class="form-group">
                            <img src="{{ $employee->photo ? url($employee->photo) : url('src/assets/images/user.png') }}" 
                                 alt="Foto Karyawan" 
                                 class="img-thumbnail mb-3" 
                                 style="max-width: 200px; height: auto;">
                            <!-- <input type="file" name="photo" class="form-control"> -->
                        </div>
                    </div>
                </div>

                <h3>Informasi Pribadi</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>No. KTP</label>
                            <input type="text" name="nik_karyawan" class="form-control" value="{{ $employee->nik_karyawan }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="{{ $employee->nama_lengkap }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control">
                                <option value="L" {{ $employee->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $employee->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ $employee->tanggal_lahir }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" value="{{ $employee->tempat_lahir }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" class="form-control">{{ $employee->alamat_lengkap }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor HP</label>
                            <input type="text" name="telepon" class="form-control" value="{{ $employee->telepon }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Golongan Darah</label>
                            <input type="text" name="golongan_darah" class="form-control" value="{{ $employee->golongan_darah }}">
                        </div>
                    </div>
                </div>

                <div class="text-left mt-4">
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    <a href="{{ route('pegawai.profile') }}" class="btn btn-light">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
