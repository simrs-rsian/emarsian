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
            <p class="card-description"><h3>Riwayat Pendidikan {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a href="{{ route('employee.show', $employee->id) }}" class="btn btn-danger btn-sm">Kembali</a>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRiwayatPendidikanModal">Tambah Riwayat Pendidikan</button>
                    </div>
                    <br>
                    <div class="table-responsive" data-simplebar>
                        <table class="table table-borderless align-middle text-nowrap" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Tahun Masuk</th>
                                    <th scope="col">Tahun Lulus</th>
                                    <th scope="col">Sekolah/Universitas</th>
                                    <th scope="col">Lokasi</th>
                                    <th scope="col">Jenis Data</th>
                                    <th scope="col">Dokumen</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatPendidikans as $key => $riwayatPendidikan)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $riwayatPendidikan->tahun_masuk ?? '-' }}</td>
                                        <td>{{ $riwayatPendidikan->tahun_lulus ?? '-' }}</td>
                                        <td>{{ $riwayatPendidikan->nama_sekolah ?? '-' }}</td>
                                        <td>{{ $riwayatPendidikan->lokasi ?? '-' }}</td>
                                        <td>{{ $riwayatPendidikan->jenis_data ?? '-' }}</td>
                                        <td>
                                            @if($riwayatPendidikan->dokumen)
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenPendidikanModal{{ $riwayatPendidikan->id }}">
                                                    Tampilalkan Dokumen
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('riwayat_pendidikan.edit', $riwayatPendidikan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('riwayat_pendidikan.destroy', $riwayatPendidikan->id) }}" method="POST" class="d-inline">
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
            @foreach($riwayatPendidikans as $key => $riwayatPendidikan)
                
                <!-- Modal untuk menampilkan gambar dokumen -->
                <div class="modal fade" id="viewDokumenPendidikanModal{{ $riwayatPendidikan->id }}" tabindex="-1" aria-labelledby="viewDokumenPendidikanModalLabel{{ $riwayatPendidikan->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewDokumenPendidikanModalLabel{{ $riwayatPendidikan->id }}">Dokumen riwayat Sekolah/Universitas {{ $riwayatPendidikan->nama_sekolah }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                @if($riwayatPendidikan->dokumen)
                                    @php
                                        $extension = pathinfo($riwayatPendidikan->dokumen, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ url($riwayatPendidikan->dokumen) }}" alt="Dokumen" class="img-fluid">
                                    @elseif($extension == 'pdf')
                                        <iframe src="{{ url($riwayatPendidikan->dokumen) }}" width="100%" height="500px"></iframe>
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
            <!-- Modal Tambah riwayat Pendidikan -->
            <div class="modal fade" id="createRiwayatPendidikanModal" tabindex="-1" aria-labelledby="createRiwayatPendidikanModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createRiwayatPendidikanModalLabel">Tambah Riwayat Pendidikan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('riwayat_pendidikan.store') }}" method="POST" enctype="multipart/form-data"> 
                            @csrf
                            <div class="modal-body">
                                <!-- Input ID Pegawai yang di-hidden -->
                                <input type="hidden" id="id_employee" name="id_employee" value="{{ $employee->id }}">

                                <div class="mb-3">
                                    <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                                    <input type="text" class="form-control" id="tahun_masuk" name="tahun_masuk" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                                    <input type="text" class="form-control" id="tahun_lulus" name="tahun_lulus" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_sekolah" class="form-label">Sekolah/Universitas</label>
                                    <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" required>
                                </div>
                                <div class="mb-3">
                                    <label for="lokasi" class="form-label">Lokasi</label>
                                    <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                                </div>
                                <div class="mb-3">
                                    <label for="lokasi" class="form-label">Jenis Data</label>
                                    <select name="jenis_data" id="jenis_data" class="form-control">
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="Transkip">Transkip</option>
                                        <option value="Ijazah">Ijazah</option>
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