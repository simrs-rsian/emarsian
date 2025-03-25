@extends('includeView.layout')
@section('content')

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Detail Data Karyawan</h4>
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <div class="form-group">
                        <!-- Display current or uploaded image -->
                        <img src="{{ $employee->photo ? url($employee->photo) : url('src/assets/images/user.png') }}" 
                             alt="Foto Karyawan" 
                             class="img-thumbnail mb-3" 
                             style="max-width: 200px; height: auto;">
                    </div>
                </div>
            </div>

            <p class="card-description"><h3>Informasi Pribadi</h3></p>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">NIK Karyawan</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->nik_karyawan }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->nama_lengkap }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->tanggal_lahir }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tempat Lahir</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->tempat_lahir }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Alamat Lengkap</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->alamat_lengkap }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nomor HP</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->telepon }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Golongan Darah</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->golongan_darah }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <p class="card-description"><h3>Informasi Karyawan</h3></p>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">NIP Karyawan</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->nip_karyawan }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Status Keluarga</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->namastatuskel }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Employee Information -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Pendidikan</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->nama_pendidikan }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jurusan</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->jurusan }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Profesi</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->nama_profesi }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Status Karyawan</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->namastatuskar }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">BPJS Kesehatan</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->bpjs_kesehatan }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">BPJS Ketenagakerjaan</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $employee->bpjs_ketenagakerjaan }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Button -->
            <div class="text-left mt-4">
                <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-primary">Edit Data</a> &nbsp;&nbsp;
                <a href="#" onclick="goBackToTable()" class="btn btn-light">Batal</a>
            </div>

<!-- ===================================================================================================++++++++++++++++ -->
            <!-- Riwayat Pendidikan -->
            @include('employee.riwayat.riwayatpendidikan')
            
<!-- ===================================================================================================++++++++++++++++ -->
            <!-- Riwayat Seminar/Pelatihan -->
            @include('employee.riwayat.riwayatpelatihan')

<!-- ===================================================================================================++++++++++++++++ -->
            <!-- Riwayat Jabatan -->
            @include('employee.riwayat.riwayatjabatan')
            
<!-- ===================================================================================================++++++++++++++++ -->
            <!-- Riwayat Keluarga -->
            @include('employee.riwayat.riwayatkeluarga')
            
<!-- ===================================================================================================++++++++++++++++ -->
            <!-- Riwayat Sipp -->
            @include('employee.riwayat.riwayatsipp')

<!-- ===================================================================================================++++++++++++++++ -->
            <!-- Riwayat Kontrak -->
            @include('employee.riwayat.riwayatkontrak')
            
<!-- ===================================================================================================++++++++++++++++ -->
            <!-- Riwayat Lainnya -->
            @include('employee.riwayat.riwayatlain')
            

        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    function goBackToTable() {
        let lastPage = localStorage.getItem("datatable_last_page") || 0;
        let baseUrl = "{{ route('employee.index') }}"; // Pastikan route ini benar

        // Redirect ke halaman yang terakhir dikunjungi
        window.location.href = baseUrl + "?page=" + (parseInt(lastPage) + 1);
    }
</script>
@endsection