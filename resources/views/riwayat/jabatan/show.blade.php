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
            <p class="card-description"><h3>Riwayat Jabatan {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a href="{{ route('employee.show', $employee->id) }}" class="btn btn-danger btn-sm">Kembali</a>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRiwayatJabatanModal">Tambah Riwayat Jabatan</button>
                    </div>
                    <br>
                    <div class="table-responsive" data-simplebar>
                        <table class="table table-borderless align-middle text-nowrap" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Tahun Mulai</th>
                                    <th scope="col">Tahun Selesai</th>
                                    <th scope="col">Dokumen</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatJabatans as $key => $riwayatJabatan)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $riwayatJabatan->tahun_mulai }}</td>
                                        <td>{{ $riwayatJabatan->tahun_selesai }}</td>
                                        <td>
                                            @if($riwayatJabatan->dokumen != null)
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenJabatanModal{{ $riwayatJabatan->id }}">
                                                    Tampilalkan Dokumen
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $riwayatJabatan->keterangan }}</td>
                                        <td>
                                            <a href="{{ route('riwayat_jabatan.edit', $riwayatJabatan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('riwayat_jabatan.destroy', $riwayatJabatan->id) }}" method="POST" class="d-inline">
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
            @foreach($riwayatJabatans as $key => $riwayatJabatan)
                
                <!-- Modal untuk menampilkan gambar dokumen -->
                <div class="modal fade" id="viewDokumenJabatanModal{{ $riwayatJabatan->id }}" tabindex="-1" aria-labelledby="viewDokumenJabatanModalLabel{{ $riwayatJabatan->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewDokumenModalJabatanLabel{{ $riwayatJabatan->id }}">Dokumen riwayat Jabatan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                @if($riwayatJabatan->dokumen)
                                    @php
                                        $extension = pathinfo($riwayatJabatan->dokumen, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ url($riwayatJabatan->dokumen) }}" alt="Dokumen" class="img-fluid">
                                    @elseif($extension == 'pdf')
                                        <iframe src="{{ url($riwayatJabatan->dokumen) }}" width="100%" height="500px"></iframe>
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
            <!-- Modal Tambah riwayat Jabatan -->
            <div class="modal fade" id="createRiwayatJabatanModal" tabindex="-1" aria-labelledby="createRiwayatJabatanModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createRiwayatJabatanModalLabel">Tambah Riwayat Jabatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('riwayat_jabatan.store') }}" method="POST" enctype="multipart/form-data"> 
                            @csrf
                            <div class="modal-body">
                                <!-- Input ID Pegawai yang di-hidden -->
                                <input type="hidden" id="id_employee" name="id_employee" value="{{ $employee->id }}">

                                <div class="mb-3">
                                    <label for="tahun_mulai" class="form-label">Tahun Mulai</label>
                                    <input type="text" class="form-control" id="tahun_mulai" name="tahun_mulai" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tahun_selesai" class="form-label">Tahun Selesai</label>
                                    <input type="text" class="form-control" id="tahun_selesai" name="tahun_selesai" required>
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">keterangan</label>
                                    <input type="text" class="form-control" id="keterangan" name="keterangan" required>
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