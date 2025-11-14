@extends('includeView.layout')
@section('content')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <p class="card-description"><h3>Edit Riwayat Lain Pegawai {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <form action="{{ route('riwayat_str.update', $riwayat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="riwayat_id" value="{{ $riwayat->id }}">

                        <div class="mb-3">
                            <label for="nama_riwayat{{ $riwayat->id }}" class="form-label">Nama Riwayat</label>
                            <input type="text" class="form-control" id="nama_riwayat{{ $riwayat->id }}" name="nama_riwayat" value="{{ $riwayat->nama_riwayat }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_riwayat{{ $riwayat->id }}" class="form-label">Tanggal Riwayat</label>
                            <input type="text" class="form-control" id="tanggal_riwayat{{ $riwayat->id }}" name="tanggal_riwayat" value="{{ $riwayat->tanggal_riwayat }}" required>
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