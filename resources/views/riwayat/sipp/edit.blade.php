@extends('includeView.layout')
@section('content')


<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <p class="card-description"><h3>Edit Riwayat SIP {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <form action="{{ route('riwayat_sipp.update', $riwayat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <!-- Input ID Pegawai yang di-hidden -->
                        <input type="hidden" id="id_riwayat" name="id_riwayat" value="{{ $riwayat->id }}">

                        <div class="mb-3">
                            <label for="no_sipp{{ $riwayat->id }}" class="form-label">No SIP</label>
                            <input type="text" class="form-control" id="no_sipp{{ $riwayat->id }}" name="no_sipp" value="{{ $riwayat->no_sipp }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_str{{ $riwayat->id }}" class="form-label">No STR</label>
                            <input type="text" class="form-control" id="no_str{{ $riwayat->id }}" name="no_str" value="{{ $riwayat->no_str }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_berlaku{{ $riwayat->id }}" class="form-label">Tanggal Akhir Berlaku</label>
                            <input type="date" class="form-control" id="tanggal_berlaku{{ $riwayat->id }}" name="tanggal_berlaku" value="{{ $riwayat->tanggal_berlaku }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="dokumen{{ $riwayat->id }}" class="form-label">Dokumen</label>
                            <input type="file" class="form-control" id="dokumen{{ $riwayat->id }}" name="dokumen">
                            * Silahkan Upload file dengan extention .pdf/.jpg/.png
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