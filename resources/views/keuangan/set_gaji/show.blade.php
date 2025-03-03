@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Setting Gaji Pegawai</h4>
                    <p class="card-description">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </p>
                    <div class="table-responsive pt-3">
                        <table class="table table-striped">
                            <tr>
                                <td style="width: 200px;">NIP Karyawan</td>
                                <td>:</td>
                                <td>{{ $employees->nip_karyawan }}</td>
                                <td style="width: 200px;">Jabatan</td>
                                <td>:</td>
                                <td>{{ $employees->unit->nama_unit }}</td>
                            </tr>
                            <tr>
                                <td>Nama Lengkap</td>
                                <td>:</td>
                                <td>{{ $employees->nama_lengkap }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="table-responsive pt-3">
                        <form action="{{ route('setting_gaji.storeOrUpdate', $employees->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $employees->id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Gaji</h5>
                                    @foreach($defaultgaji as $gaji)
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">{{ $gaji->gaji_nama }}</label>
                                            <div class="col-sm-8">
                                                <input type="number" name="gaji[{{ $gaji->id }}]" class="form-control" value="{{ $settingGaji[$gaji->id]->nominal ?? '0' }}" required>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="col-md-6">
                                    <h5>Potongan</h5>
                                    @foreach($defaultpotongan as $potongan)
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">{{ $potongan->gaji_nama }}</label>
                                            <div class="col-sm-8">
                                                <input type="number" name="potongan[{{ $potongan->id }}]" class="form-control" value="{{ $settingPotongan[$potongan->id]->nominal ?? '0' }}" required>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                            <a href="{{ route('setting_gaji.index') }}" class="btn btn-danger">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan script inisialisasi DataTable setelah halaman siap -->


@endsection
