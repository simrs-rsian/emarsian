@extends('includeView.layout')
@section('content')

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <h4 class="card-title">Edit Data Karyawan</h4>
            <form class="form-sample" action="{{ route('employee.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Image Upload Section -->
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <div class="form-group">
                            <!-- Display current or uploaded image -->
                            <img src="{{ $employee->photo ? url('storage/' . $employee->photo) : url('src/assets/images/user.png') }}" 
                                 alt="Foto Karyawan" 
                                 id="image-preview" 
                                 class="img-thumbnail mb-3" 
                                 style="max-width: 200px; height: auto;">
                            <!-- Input for image upload -->
                            <input type="file" name="photo" class="form-control file-upload-info" accept="image/*" onchange="previewImage(event)">
                        </div>
                    </div>
                </div>
                <p class="card-description"><h3>Informasi Pribadi</h3></p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">NIK Karyawan</label>
                            <div class="col-sm-9">
                                <input type="text" name="nik_karyawan" class="form-control" value="{{ $employee->nik_karyawan }}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ $employee->nama_lengkap }}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-9">
                                <select class="form-select" style="color: black;" name="jenis_kelamin">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ $employee->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ $employee->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-9">
                                <input type="date" name="tanggal_lahir" class="form-control" value="{{ $employee->tanggal_lahir }}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tempat Lahir</label>
                            <div class="col-sm-9">
                                <input type="text" name="tempat_lahir" class="form-control" value="{{ $employee->tempat_lahir }}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Alamat Lengkap</label>
                            <div class="col-sm-9">
                                <input type="text" name="alamat_lengkap" class="form-control" value="{{ $employee->alamat_lengkap }}" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input untuk Nomor HP -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Nomor HP</label>
                            <div class="col-sm-9">
                                <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx" value="{{ $employee->telepon }}" />
                            </div>
                        </div>
                    </div>

                    <!-- Input untuk Upload photo -->
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Golongan Darah</label>
                            <div class="col-sm-9">
                                <select class="form-select" style="color: black;" name="golongan_darah">
                                    <option value="">Pilih Golongan Darah</option>
                                    <option value="-" {{ $employee->golongan_darah == '-' ? 'selected' : '' }}>-</option>
                                    <option value="A" {{ $employee->golongan_darah == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ $employee->golongan_darah == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="O" {{ $employee->golongan_darah == 'O' ? 'selected' : '' }}>O</option>
                                    <option value="AB" {{ $employee->golongan_darah == 'AB' ? 'selected' : '' }}>AB</option>
                                </select>
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
                                <input type="text" name="nip_karyawan" class="form-control" value="{{ $employee->nip_karyawan }}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status Keluarga</label>
                            <div class="col-sm-9">
                                <select class="form-select select2-keluarga" style="color: black;" name="status_keluarga">
                                    <option value="">Pilih Status</option>
                                    @foreach($statuskeluargas as $status)
                                        <option value="{{ $status->id }}" {{ $employee->status_keluarga == $status->id ? 'selected' : '' }}>
                                            {{ $status->nama_status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pendidikan</label>
                            <div class="col-sm-9">
                                <select class="form-select select2-pendidikan" style="color: black;" name="pendidikan">
                                    <option value="">Pilih Pendidikan Terakhir</option>
                                    @foreach($pendidikans as $pendidikan)
                                        <option value="{{ $pendidikan->id }}" {{ $employee->pendidikan == $pendidikan->id ? 'selected' : '' }}>
                                            {{ $pendidikan->nama_pendidikan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jurusan</label>
                            <div class="col-sm-9">
                                <input type="text" name="jurusan" class="form-control" placeholder="Jurusan dari Pendidikan" value="{{ $employee->jurusan }}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Profesi</label>
                            <div class="col-sm-9">
                                <select class="form-select select2-profesi" style="color: black;" name="profesi">
                                    <option value="">Pilih Profesi</option>
                                    @foreach($profesis as $profesi)
                                        <option value="{{ $profesi->id }}" {{ $employee->profesi == $profesi->id ? 'selected' : '' }}>
                                            {{ $profesi->nama_profesi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Status Karyawan</label>
                            <div class="col-sm-9">
                                <select class="form-select select2-status" style="color: black;" name="status_karyawan">
                                    <option value="">Pilih Status</option>
                                    @foreach($statuskaryawans as $status)
                                        <option value="{{ $status->id }}" {{ $employee->status_karyawan == $status->id ? 'selected' : '' }}>
                                            {{ $status->nama_status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Jabatan Struktural</label>
                            <div class="col-sm-9">
                                <select class="form-select select2-jabatan" style="color: black;" name="jabatan_struktural">
                                    <option value="">Pilih Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ $employee->jabatan_struktural == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->nama_unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Golongan</label>
                            <div class="col-sm-9">
                                <select class="form-select select2-golongan" style="color: black;" name="golongan">
                                    <option value="">Pilih Golongan</option>
                                    @foreach($golongans as $golongan)
                                        <option value="{{ $golongan->id }}" {{ $employee->golongan == $golongan->id ? 'selected' : '' }}>
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
                                <input type="text" name="bpjs_kesehatan" class="form-control" placeholder="No BPJS Kesehatan" value="{{ $employee->bpjs_kesehatan }}" required/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">BPJS Ketenagakerjaan </label>
                            <div class="col-sm-9">
                                <input type="text" name="bpjs_ketenagakerjaan" class="form-control" placeholder="No BPJS Ketenagakerjaan" value="{{ $employee->bpjs_ketenagakerjaan }}" required/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">NPWP</label>
                            <div class="col-sm-9">
                                <input type="text" name="npwp" class="form-control" placeholder="No NPWP" value="{{ $employee->npwp }}" required/>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Mulai Bekerja (TMT)</label>
                            <div class="col-sm-9">
                                <input type="date" name="tmt" class="form-control" value="{{ $employee->tmt }}" required/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Akhir Bekerja (TMTA)</label>
                            <div class="col-sm-9">
                                <input type="date" name="tmta" class="form-control" value="{{ $employee->tmta }}"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="javascript:history.back()" class="btn btn-light">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('image-preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection