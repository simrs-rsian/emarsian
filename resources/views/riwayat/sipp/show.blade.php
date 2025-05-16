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
            <p class="card-description"><h3>Riwayat SIP {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a href="{{ route('employee.show', $employee->id) }}" class="btn btn-danger btn-sm">Kembali</a>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRiwayatSippModal">Tambah Riwayat SIP</button>
                    </div>
                    <br>
                    <div class="table-responsive" data-simplebar>
                        <table class="table table-borderless align-middle text-nowrap" id="dataTable">                
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">No SIP</th>
                                    <th scope="col">No STR</th>
                                    <th scope="col">Tanggal Akhir Berlaku</th>
                                    <th scope="col">Dokumen</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatSipps as $key => $riwayatSipp)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $riwayatSipp->no_sipp }}</td>
                                        <td>{{ $riwayatSipp->no_str }}</td>
                                        <td>{{ $riwayatSipp->tanggal_berlaku }}</td>
                                        <td>
                                            @if($riwayatSipp->dokumen)
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenSippModal{{ $riwayatSipp->id }}">
                                                    Tampilalkan Dokumen
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('riwayat_sipp.edit', $riwayatSipp->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('riwayat_sipp.destroy', $riwayatSipp->id) }}" method="POST" class="d-inline">
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
            @foreach($riwayatSipps as $key => $riwayatSipp)
                
                <!-- Modal untuk menampilkan gambar dokumen -->
                <div class="modal fade" id="viewDokumenSippModal{{ $riwayatSipp->id }}" tabindex="-1" aria-labelledby="viewDokumenSippModalLabel{{ $riwayatSipp->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewDokumenSippModalLabel{{ $riwayatSipp->id }}">Dokumen riwayat SIP dengan Nomor.  {{ $riwayatSipp->no_sipp }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                @if($riwayatSipp->dokumen)
                                    @php
                                        $extension = pathinfo($riwayatSipp->dokumen, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ url($riwayatSipp->dokumen) }}" alt="Dokumen" class="img-fluid">
                                    @elseif($extension == 'pdf')
                                        <iframe src="{{ url($riwayatSipp->dokumen) }}" width="100%" height="500px"></iframe>
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
            <!-- Modal Tambah Riwayat SIP -->
            <div class="modal fade" id="createRiwayatSippModal" tabindex="-1" aria-labelledby="createRiwayatSippModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createRiwayatSippModalLabel">Tambah Riwayat SIP</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('riwayat_sipp.store') }}" method="POST" enctype="multipart/form-data"> 
                            @csrf
                            <div class="modal-body">
                                <!-- Input ID Pegawai yang di-hidden -->
                                <input type="hidden" id="id_employee" name="id_employee" value="{{ $employee->id }}">

                                <div class="mb-3">
                                    <label for="no_sipp" class="form-label">No. SIP</label>
                                    <input type="text" class="form-control" id="no_sipp" name="no_sipp" required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_str" class="form-label">No. STR</label>
                                    <input type="text" class="form-control" id="no_str" name="no_str" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal_berlaku" class="form-label">Tanggal Akhir Berlaku</label>
                                    <input type="date" class="form-control" id="tanggal_berlaku" name="tanggal_berlaku" required>
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