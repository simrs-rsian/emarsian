@extends('includeView.layout')
@section('content')


<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
          <!-- buat session edit berhasil atau error -->
            @if(session('success'))
              <div class="alert alert-success show" id="alert-session-success">
                {{ session('success') }}
              </div>
            @elseif(session('error'))
              <div class="alert alert-danger show" id="alert-session-error">
                {{ session('error') }}
              </div>
            @endif

            <script>
              setTimeout(function() {
              let alertSuccess = document.getElementById('alert-session-success');
              let alertError = document.getElementById('alert-session-error');
              if(alertSuccess) {
                alertSuccess.classList.remove('show');
                alertSuccess.classList.add('hide');
              }
              if(alertError) {
                alertError.classList.remove('show');
                alertError.classList.add('hide');
              }
              }, 3000);
            </script>
            <p class="card-description"><h3>Riwayat Lain {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a href="{{ route('employee.show', $employee->id) }}" class="btn btn-danger btn-sm">Kembali</a>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRiwayatLainModal">Tambah Riwayat Lain</button>
                    </div>
                    <br>
                    <div class="table-responsive" data-simplebar>
                        <table class="table table-borderless align-middle text-nowrap" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Riwayat</th>
                                    <th scope="col">Tanggal Riwayat</th>
                                    <th scope="col">Dokumen</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatLains as $key => $riwayatLain)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $riwayatLain->nama_riwayat }}</td>
                                        <td>{{ $riwayatLain->tanggal_riwayat }}</td>
                                        <td>
                                            @if($riwayatLain->dokumen)
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenLainModal{{ $riwayatLain->id }}">
                                                    Tampilalkan Dokumen
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('riwayat_lain.edit', $riwayatLain->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('riwayat_lain.destroy', $riwayatLain->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @foreach($riwayatLains as $key => $riwayatLain)
                
                <!-- Modal untuk menampilkan gambar dokumen -->
                <div class="modal fade" id="viewDokumenLainModal{{ $riwayatLain->id }}" tabindex="-1" aria-labelledby="viewDokumenLainModalLabel{{ $riwayatLain->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewDokumenModalJabatanLabel{{ $riwayatLain->id }}">Dokumen Riwayat Lain</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                @if($riwayatLain->dokumen)
                                    @php
                                        $extension = pathinfo($riwayatLain->dokumen, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ url($riwayatLain->dokumen) }}" alt="Dokumen" class="img-fluid">
                                    @elseif($extension == 'pdf')
                                        <iframe src="{{ url($riwayatLain->dokumen) }}" width="100%" height="500px"></iframe>
                                    @else
                                        <p>Tipe dokumen tidak didukung.</p>
                                    @endif
                                @else
                                    <p>Dokumen tidak tersedia.</p>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- Modal Tambah Riwayat Lain -->
            <div class="modal fade" id="createRiwayatLainModal" tabindex="-1" aria-labelledby="createRiwayatLainModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createRiwayatLainModalLabel">Tambah Riwayat Lain</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('riwayat_lain.store') }}" method="POST" enctype="multipart/form-data"> 
                            @csrf
                            <div class="modal-body">
                                <!-- Input ID Pegawai yang di-hidden -->
                                <input type="hidden" id="id_employee" name="id_employee" value="{{ $employee->id }}">

                                <div class="mb-3">
                                    <label for="nama_riwayat" class="form-label">Nama Riwayat</label>
                                    <input type="text" class="form-control" id="nama_riwayat" name="nama_riwayat" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal_riwayat" class="form-label">Tanggal Riwayat</label>
                                    <input type="date" class="form-control" id="tanggal_riwayat" name="tanggal_riwayat" required>
                                </div>
                                <div class="mb-3">
                                    <label for="dokumen" class="form-label">Dokumen</label>
                                    <input type="file" class="form-control" id="dokumen" name="dokumen">
                                    * Silahkan Upload file dengan extention .pdf/.jpg/.png
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
@endsection