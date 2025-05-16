@extends('includeView.layout')
@section('content')


<div class="col-12 grid-margin">
    <div class="card">  
        <div class="card-body">
            <p class="card-description"><h3>Riwayat Keluarga {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <div>
                    <h4 class="modal-title">Edit Riwayat Keluarga</h4>                    
                </div>
                <form action="{{ route('riwayat_keluarga.update', $riwayat->id) }}" method="POST"  enctype="multipart/form-data"> 
                    @csrf
                    @method('PUT')
                    <div>
                        <!-- Input ID Pegawai yang di-hidden -->
                        <input type="hidden" id="id_riwayat" name="id_riwayat" value="{{ $riwayat->id }}">

                        <div class="mb-3">
                            <label for="nama_keluarga{{ $riwayat->id }}" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_keluarga{{ $riwayat->id }}" name="nama_keluarga" value="{{ $riwayat->nama_keluarga }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status_keluarga{{ $riwayat->id }}" class="form-label">Status Keluarga</label>
                            <select name="status_keluarga" id="status_keluarga{{ $riwayat->id }}" class="form-control" required>
                                <option value="Bapak" {{ $riwayat->status_keluarga == 'Bapak' ? 'selected' : '' }}>Bapak</option>
                                <option value="Ibu" {{ $riwayat->status_keluarga == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                                <option value="Anak" {{ $riwayat->status_keluarga == 'Anak' ? 'selected' : '' }}>Anak</option>
                                <option value="Kakek" {{ $riwayat->status_keluarga == 'Kakek' ? 'selected' : '' }}>Kakek</option>
                                <option value="Nenek" {{ $riwayat->status_keluarga == 'Nenek' ? 'selected' : '' }}>Nenek</option>
                                <option value="Lainnya" {{ $riwayat->status_keluarga == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pekerjaan_keluarga{{ $riwayat->id }}" class="form-label">Pekerjaan</label>
                            <input type="text" class="form-control" id="pekerjaan_keluarga{{ $riwayat->id }}" name="pekerjaan_keluarga" value="{{ $riwayat->pekerjaan_keluarga }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pendidikan_keluarga{{ $riwayat->id }}" class="form-label">Pendidikan Terakhir</label>
                            <select name="pendidikan_keluarga" id="pendidikan_keluarga{{ $riwayat->id }}" class="form-control" required>
                                <option value="SD" {{ $riwayat->pendidikan_keluarga == 'SD' ? 'selected' : '' }}>SD</option>
                                <option value="SMP" {{ $riwayat->pendidikan_keluarga == 'SMP' ? 'selected' : '' }}>SMP</option>
                                <option value="SMA" {{ $riwayat->pendidikan_keluarga == 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="Diploma" {{ $riwayat->pendidikan_keluarga == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="Sarjana" {{ $riwayat->pendidikan_keluarga == 'Sarjana' ? 'selected' : '' }}>Sarjana</option>
                                <option value="Lainnya" {{ $riwayat->pendidikan_keluarga == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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