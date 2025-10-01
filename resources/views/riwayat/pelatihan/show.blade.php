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
            <p class="card-description"><h3>Riwayat Pelatihan {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a href="{{ route('employee.show', $employee->id) }}" class="btn btn-danger btn-sm">Kembali</a>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRiwayatPelatihanModal">Tambah Riwayat Pelatihan</button>
                    </div>
                    <br>
                    <div class="table-responsive" data-simplebar>
                        <table class="table table-borderless align-middle text-nowrap" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Pelatihan</th>
                                    <th scope="col">Tanggal Mulai</th>
                                    <th scope="col">Tanggal Selesai</th>
                                    <th scope="col">Penyelenggara</th>
                                    <th scope="col">Lokasi</th>
                                    <th scope="col">Poin</th>
                                    <th scope="col">Jenis</th>
                                    <th scope="col">Dokumen</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatPelatihans as $key => $riwayatPelatihan)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $riwayatPelatihan->nama_pelatihan }}</td>
                                        <td>{{ $riwayatPelatihan->tanggal_mulai }}</td>
                                        <td>{{ $riwayatPelatihan->tanggal_selesai }}</td>
                                        <td>{{ $riwayatPelatihan->penyelenggara }}</td>
                                        <td>{{ $riwayatPelatihan->lokasi }}</td>
                                        <td>{{ $riwayatPelatihan->poin }}</td>
                                        <td>{{ $riwayatPelatihan->nama_jenis }}</td>
                                        <td>
                                            @if($riwayatPelatihan->dokumen)
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenPelatihanModal{{ $riwayatPelatihan->id }}">
                                                    Tampilalkan Dokumen
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('riwayat_pelatihan.edit', $riwayatPelatihan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('riwayat_pelatihan.destroy', $riwayatPelatihan->id) }}" method="POST" class="d-inline">
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
            @foreach($riwayatPelatihans as $key => $riwayatPelatihan)
                
                <!-- Modal untuk menampilkan gambar dokumen -->
                <div class="modal fade" id="viewDokumenPelatihanModal{{ $riwayatPelatihan->id }}" tabindex="-1" aria-labelledby="viewDokumenModalPelatihanLabel{{ $riwayatPelatihan->id }}" >
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewDokumenModalPelatihanLabel{{ $riwayatPelatihan->id }}">Dokumen riwayat Pelatihan {{ $riwayatPelatihan->penyelenggara }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                @if($riwayatPelatihan->dokumen)
                                    @php
                                        $extension = pathinfo($riwayatPelatihan->dokumen, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ url($riwayatPelatihan->dokumen) }}" alt="Dokumen" class="img-fluid">
                                    @elseif($extension == 'pdf')
                                        <iframe src="{{ url($riwayatPelatihan->dokumen) }}" width="100%" height="500px"></iframe>
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
            <!-- Modal Tambah Riwayat Pelatihan -->
            <div class="modal fade" id="createRiwayatPelatihanModal" tabindex="-1" aria-labelledby="createRiwayatPelatihanModalLabel" >
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createRiwayatPelatihanModalLabel">Tambah Riwayat Pelatihan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('riwayat_pelatihan.store') }}" method="POST" enctype="multipart/form-data"> 
                            @csrf
                            <div class="modal-body">
                                <!-- Input ID Pegawai yang di-hidden -->
                                <input type="hidden" id="id_employee" name="id_employee" value="{{ $employee->id }}">

                                <div class="mb-3">
                                    <label for="nama_pelatihan" class="form-label">Nama Pelatihan</label>
                                    <select class="form-select select2-keluarga" style="color: black;"   name="id_pelatihan" required>
                                        <option value="">Pilih Nama Pelatihan</option>
                                        @foreach($pelatihans as $pelatihan)
                                            <option value="{{ $pelatihan->id }}" {{ old('id_pelatihan') == $pelatihan->id ? 'selected' : '' }}>
                                                {{ $pelatihan->nama_pelatihan }}
                                            </option>
                                        @endforeach
                                    </select>
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