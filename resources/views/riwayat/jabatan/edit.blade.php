@extends('includeView.layout')
@section('content')


<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <p class="card-description"><h3>Riwayat Jabatan {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <div>
                    <h4 class="modal-title">Edit Riwayat Jabatan</h4>                    
                </div>
                <form action="{{ route('riwayat_jabatan.update', $riwayat->id) }}" method="POST"  enctype="multipart/form-data"> 
                    @csrf
                    @method('PUT')
                    <div>
                        <!-- Input ID Pegawai yang di-hidden -->
                        <input type="hidden" id="id_riwayat" name="id_riwayat" value="{{ $riwayat->id }}">

                        <div class="mb-3">
                            <label for="tahun_mulai{{ $riwayat->id }}" class="form-label">Tahun Masuk</label>
                            <input type="text" class="form-control" id="tahun_mulai{{ $riwayat->id }}" name="tahun_mulai" value="{{ $riwayat->tahun_mulai }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="tahun_selesai{{ $riwayat->id }}" class="form-label">Tahun Lulus</label>
                            <input type="text" class="form-control" id="tahun_selesai{{ $riwayat->id }}" name="tahun_selesai" value="{{ $riwayat->tahun_selesai }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan{{ $riwayat->id }}" class="form-label">keterangan/Universitas</label>
                            <input type="text" class="form-control" id="keterangan{{ $riwayat->id }}" name="keterangan" value="{{ $riwayat->keterangan }}" required>
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