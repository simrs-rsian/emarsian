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
            <p class="card-description"><h3>Riwayat Keluarga {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a href="{{ route('employee.show', $employee->id) }}" class="btn btn-danger btn-sm">Kembali</a>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRiwayatKeluargaModal">Tambah Riwayat Keluarga</button>
                    </div>
                    <br>
                    <div class="table-responsive" data-simplebar>
                        <table class="table table-borderless align-middle text-nowrap" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Lengkap</th>
                                    <th scope="col">Status Keluarga</th>
                                    <th scope="col">Pekerjaan</th>
                                    <th scope="col">Pendidikan Terkahir</th>
                                    <th scope="col">Dokumen</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="riwayatPendidikanTable">
                                @foreach($riwayatKeluargas as $key => $riwayatKeluarga)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $riwayatKeluarga->nama_keluarga }}</td>
                                        <td>{{ $riwayatKeluarga->status_keluarga }}</td>
                                        <td>{{ $riwayatKeluarga->pekerjaan_keluarga }}</td>
                                        <td>{{ $riwayatKeluarga->pendidikan_keluarga }}</td>
                                        <td>
                                            @if($riwayatKeluarga->dokumen)
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenKeluargaModal{{ $riwayatKeluarga->id }}">
                                                    Tampilalkan Dokumen
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('riwayat_keluarga.edit', $riwayatKeluarga->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('riwayat_keluarga.destroy', $riwayatKeluarga->id) }}" method="POST" class="d-inline">
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
            @foreach($riwayatKeluargas as $key => $riwayatKeluarga)
                
                <!-- Modal untuk menampilkan gambar dokumen -->
                <div class="modal fade" id="viewDokumenKeluargaModal{{ $riwayatKeluarga->id }}" tabindex="-1" aria-labelledby="viewDokumenKeluargaModalLabel{{ $riwayatKeluarga->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewDokumenModalJabatanLabel{{ $riwayatKeluarga->id }}">Dokumen Riwayat Keluarga</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                @if($riwayatKeluarga->dokumen)
                                    @php
                                        $extension = pathinfo($riwayatKeluarga->dokumen, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ url($riwayatKeluarga->dokumen) }}" alt="Dokumen" class="img-fluid">
                                    @elseif($extension == 'pdf')
                                        <iframe src="{{ url($riwayatKeluarga->dokumen) }}" width="100%" height="500px"></iframe>
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
            <!-- Modal Tambah RIwayat Keluarga -->
            <div class="modal fade" id="createRiwayatKeluargaModal" tabindex="-1" aria-labelledby="createRiwayatKeluargaModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createRiwayatKeluargaModalLabel">Tambah Riwayat Keluarga</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('riwayat_keluarga.store') }}" method="POST" enctype="multipart/form-data"> 
                            @csrf
                            <div class="modal-body">
                                <!-- Input ID Pegawai yang di-hidden -->
                                <input type="hidden" name="id_employee" value="{{ $employee->id }}">

                                <div class="mb-3">
                                    <label for="nama_keluarga" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_keluarga" name="nama_keluarga" required>
                                </div>

                                <div class="mb-3">
                                    <label for="status_keluarga" class="form-label">Status Keluarga</label>
                                    <select name="status_keluarga" id="status_keluarga" class="form-control" required>
                                        <option value="">Pilih status keluarga</option>
                                        <option value="Bapak">Bapak</option>
                                        <option value="Ibu">Ibu</option>
                                        <option value="Anak">Anak</option>
                                        <option value="Kakek">Kakek</option>
                                        <option value="Nenek">Nenek</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="pekerjaan_keluarga" class="form-label">Pekerjaan</label>
                                    <input type="text" class="form-control" id="pekerjaan_keluarga" name="pekerjaan_keluarga" required>
                                </div>

                                <div class="mb-3">
                                    <label for="pendidikan_keluarga" class="form-label">Pendidikan Terakhir</label>
                                    <select name="pendidikan_keluarga" id="pendidikan_keluarga" class="form-control" required>
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA">SMA</option>
                                        <option value="Diploma">Diploma</option>
                                        <option value="Sarjana">Sarjana</option>
                                        <option value="Lainnya">Lainnya</option>
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