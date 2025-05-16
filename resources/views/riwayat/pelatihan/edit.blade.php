@extends('includeView.layout')
@section('content')


<div class="col-12 grid-margin">
    <div class="card">  
        <div class="card-body">
            <p class="card-description"><h3>Riwayat Pelatihan {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <div>
                    <h4 class="modal-title">Edit Riwayat Pelatihan</h4>                    
                </div>
                <form action="{{ route('riwayat_pelatihan.update', $riwayat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="id_employee" value="{{ $riwayat->id_employee }}">
                        <input type="hidden" name="riwayat_id" value="{{ $riwayat->id }}">
                        
                        <div class="mb-3">
                            <label for="nama_pelatihan{{ $riwayat->id }}" class="form-label">Nama Pelatihan</label>
                            <select class="form-select select2-keluarga" style="color: black;"   name="id_pelatihan" required>
                                <option value="">Pilih Nama Pelatihan</option>
                                @foreach($pelatihans as $pelatihan)
                                    <option value="{{ $pelatihan->id }}" {{ $riwayat->id_pelatihan == $pelatihan->id ? 'selected' : '' }}>
                                        {{ $pelatihan->nama_pelatihan }}
                                    </option>
                                @endforeach
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