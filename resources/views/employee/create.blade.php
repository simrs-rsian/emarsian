@extends('includeView.layout')
@section('content')

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <!-- Tampilkan error validasi jika ada -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h4 class="card-title">Tambah Data Karyawan</h4>
            <form class="form-sample" action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Bagian Unggah Foto Karyawan -->
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <div class="form-group">
                            <!-- Pratinjau Gambar -->
                            <img src="{{ url('src/assets/images/user.png') }}" 
                                 alt="Foto Karyawan" 
                                 id="image-preview" 
                                 class="img-thumbnail mb-3" 
                                 style="max-width: 200px; height: auto;">
                            <!-- Input File untuk Unggah Gambar -->
                            <input type="file" name="photo" class="form-control file-upload-info" accept="image/*" onchange="previewImage(event)">
                        </div>
                    </div>
                </div>

                <p class="card-description"><h3>Informasi Pribadi</h3></p>
                
                <!-- Informasi Pribadi -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">NIK Karyawan</label>
                            <div class="col-sm-9">
                                <input type="text" name="nik_karyawan" class="form-control" value="{{ old('nik_karyawan') }}" required/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" required/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jenis Kelamin dan Tanggal Lahir -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-9">
                                <select class="form-select" style="color: black;"   name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-9">
                                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tempat Lahir dan Alamat Lengkap -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tempat Lahir</label>
                            <div class="col-sm-9">
                                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}" required/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Alamat Lengkap</label>
                            <div class="col-sm-9">
                                <input type="text" name="alamat_lengkap" class="form-control" value="{{ old('alamat_lengkap') }}" required/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nomor HP dan Golongan Darah -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nomor HP</label>
                            <div class="col-sm-9">
                                <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('telepon') }}" required/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Golongan Darah</label>
                            <div class="col-sm-9">
                                <select class="form-select" style="color: black;"   name="golongan_darah" required>
                                    <option value="">Pilih Golongan Darah</option>
                                    <option value="A" {{ old('golongan_darah') == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('golongan_darah') == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="O" {{ old('golongan_darah') == 'O' ? 'selected' : '' }}>O</option>
                                    <option value="AB" {{ old('golongan_darah') == 'AB' ? 'selected' : '' }}>AB</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="card-description"><h3>Informasi Karyawan</h3></p>
                
                <!-- Informasi Karyawan -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">NIP Karyawan</label>
                            <div class="col-sm-9">
                                <input type="text" name="nip_karyawan" class="form-control" value="{{ old('nip_karyawan') }}" required/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status Keluarga</label>
                            <div class="col-sm-9">
                                <select class="form-select" style="color: black;"   name="status_keluarga" required>
                                    <option value="">Pilih Status</option>
                                    @foreach($statuskeluargas as $status)
                                        <option value="{{ $status->id }}" {{ old('status_keluarga') == $status->id ? 'selected' : '' }}>
                                            {{ $status->nama_status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pendidikan dan Profesi -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pendidikan</label>
                            <div class="col-sm-9">
                                <select class="form-select" style="color: black;"   name="pendidikan" required>
                                    <option value="">Pilih Pendidikan Terakhir</option>
                                    @foreach($pendidikans as $pendidikan)
                                        <option value="{{ $pendidikan->id }}" {{ old('pendidikan') == $pendidikan->id ? 'selected' : '' }}>
                                            {{ $pendidikan->nama_pendidikan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Profesi</label>
                            <div class="col-sm-9">
                                <select class="form-select" style="color: black;"   name="profesi" required>
                                    <option value="">Pilih Profesi</option>
                                    @foreach($profesis as $profesi)
                                        <option value="{{ $profesi->id }}" {{ old('profesi') == $profesi->id ? 'selected' : '' }}>
                                            {{ $profesi->nama_profesi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Karyawan, Jabatan Struktural, Golongan -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status Karyawan</label>
                            <div class="col-sm-9">
                            <select class="form-select" style="color: black;"   name="status_karyawan" required>
                                    <option value="">Pilih Status Karyawan</option>
                                    @foreach($statuskaryawans as $status)
                                        <option value="{{ $status->id }}" {{ old('status_karyawan') == $status->id ? 'selected' : '' }}>
                                            {{ $status->nama_status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Jabatan Struktural -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jabatan Struktural</label>
                            <div class="col-sm-9">
                                <select class="form-select" style="color: black;"   name="jabatan_struktural" required>
                                    <option value="">Pilih Jabatan Struktural</option>
                                    @foreach($units as $jabatan)
                                        <option value="{{ $jabatan->id }}" {{ old('jabatan_struktural') == $jabatan->id ? 'selected' : '' }}>
                                            {{ $jabatan->nama_unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Golongan -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Golongan</label>
                            <div class="col-sm-9">
                                <select class="form-select" style="color: black;"   name="golongan" required>
                                    <option value="">Pilih Golongan</option>
                                    @foreach($golongans as $golongan)
                                        <option value="{{ $golongan->id }}" {{ old('golongan') == $golongan->id ? 'selected' : '' }}>
                                            {{ $golongan->nama_golongan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- BPJS Kesehatan -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">BPJS Kesehatan</label>
                            <div class="col-sm-9">
                                <input type="text" name="bpjs_kesehatan" class="form-control" placeholder="No BPJS Kesehatan" value="{{ old('bpjs_kesehatan') }}" required/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BPJS Ketenagakerjaan -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">BPJS Ketenagakerjaan</label>
                            <div class="col-sm-9">
                                <input type="text" name="bpjs_ketenagakerjaan" class="form-control" placeholder="No BPJS Ketenagakerjaan" value="{{ old('bpjs_ketenagakerjaan') }}" required/>
                            </div>
                        </div>
                    </div>

                    <!-- NPWP -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">NPWP</label>
                            <div class="col-sm-9">
                                <input type="text" name="npwp" class="form-control" placeholder="No NPWP" value="{{ old('npwp') }}" required/>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tanggal Pengangkatan -->
                <div class="row">                    
                    <!-- Tanggal Mulai Bekerja -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Mulai Bekerja (TMT)</label>
                            <div class="col-sm-9">
                                <input type="date" name="tmt" class="form-control" value="{{ old('tmt') }}" required/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Akhir Bekerja (TMTA)</label>
                            <div class="col-sm-9">
                                <input type="date" name="tmta" class="form-control" value="{{ old('tmta') }}"/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('employee.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk preview gambar sebelum upload
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('image-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
