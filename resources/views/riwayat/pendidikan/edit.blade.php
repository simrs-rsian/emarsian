@extends('includeView.layout')
@section('content')


<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <p class="card-description"><h3>Edit Riwayat Pendidikan {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <form action="{{ route('riwayat_pendidikan.update', $riwayat->id) }}" method="POST"  enctype="multipart/form-data"> 
                    @csrf
                    @method('PUT')
                    <div>
                        <!-- Input ID Pegawai yang di-hidden -->
                        <input type="hidden" id="id_riwayat" name="id_riwayat" value="{{ $riwayat->id }}">

                        <div class="mb-3">
                            <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                            <input type="text" class="form-control" id="tahun_masuk" name="tahun_masuk" value="{{ $riwayat->tahun_masuk }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                            <input type="text" class="form-control" id="tahun_lulus" name="tahun_lulus" value="{{ $riwayat->tahun_lulus }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_sekolah" class="form-label">Sekolah/Universitas</label>
                            <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" value="{{ $riwayat->nama_sekolah }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ $riwayat->lokasi }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_data" class="form-label">Jenis Data</label>
                            <select name="jenis_data" id="jenis_data" class="form-control">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Transkip" {{  $riwayat->jenis_data == 'Transkip' ? 'selected' : ''  }}>Transkip</option>
                                <option value="Ijazah" {{  $riwayat->jenis_data == 'Ijazah' ? 'selected' : ''  }}>{{ $riwayat->jenis_data }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dokumen" class="form-label">Dokumen</label>
                            <input type="file" class="form-control" id="dokumen" name="dokumen">
                            * Silahkan Upload file dengan extention .pdf/.jpg/.png <br>
                            ** Abaikan Jika tidak ingin mengubah dokumen
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="window.history.back();">Kembali</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
@endsection