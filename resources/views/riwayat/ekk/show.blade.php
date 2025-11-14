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
            <p class="card-description"><h3>Data EKK {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a href="{{ route('employee.show', $employee->id) }}" class="btn btn-danger btn-sm">Kembali</a>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createriwayatStrModal">Tambah Data EKK</button>
                    </div>
                    <br>
                    <div class="table-responsive" data-simplebar>
                        <table class="table table-borderless align-middle text-nowrap" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Data</th>
                                    <th scope="col">Tanggal Riwayat</th>
                                    <th scope="col">Dokumen</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatEkks as $key => $riwayatEkk)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $riwayatEkk->nama_riwayat }}</td>
                                        <td>{{ $riwayatEkk->tanggal_riwayat }}</td>
                                        <td>
                                            @if($riwayatEkk->dokumen)
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenLainModal{{ $riwayatEkk->id }}">
                                                    Tampilalkan Dokumen
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('riwayat_ekk.edit', $riwayatEkk->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('riwayat_ekk.destroy', $riwayatEkk->id) }}" method="POST" class="d-inline">
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
            @foreach($riwayatEkks as $key => $riwayatEkk)
                <!-- Modal untuk menampilkan gambar dokumen -->
                <div class="modal fade" id="viewDokumenLainModal{{ $riwayatEkk->id }}" tabindex="-1" aria-labelledby="viewDokumenLainModalLabel{{ $riwayatEkk->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewDokumenModalJabatanLabel{{ $riwayatEkk->id }}">Dokumen Riwayat Lain</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                @if($riwayatEkk->dokumen)
                                    @php
                                        $extension = pathinfo($riwayatEkk->dokumen, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ url($riwayatEkk->dokumen) }}" alt="Dokumen" class="img-fluid">
                                    @elseif($extension == 'pdf')
                                        <iframe src="{{ url($riwayatEkk->dokumen) }}" width="100%" height="500px"></iframe>
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
            <div class="modal fade" id="createriwayatStrModal" tabindex="-1" aria-labelledby="createriwayatStrModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createriwayatStrModalLabel">Tambah Data EKK</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        
                        <form action="{{ route('riwayat_ekk.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <!-- Hidden ID Pegawai -->
                                <input type="hidden" id="id_employee" name="id_employee" value="{{ $employee->id }}">

                                <div class="mb-3">
                                    <label for="nama_riwayat" class="form-label">Nama Data</label>
                                    <input type="text" class="form-control" id="nama_riwayat" name="nama_riwayat" required>
                                </div>

                                <div class="mb-3">
                                    <label for="tanggal_riwayat" class="form-label">Tanggal Riwayat</label>
                                    <input type="date" class="form-control" id="tanggal_riwayat" name="tanggal_riwayat" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dokumen</label>
                                    <div id="file-upload-wrapper">
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" name="dokumen[]" accept=".pdf,.jpg,.jpeg,.png">
                                            <button type="button" class="btn btn-danger remove-field d-none">Hapus</button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-success" id="add-file-field">+ Tambah File</button>
                                    <small class="text-muted d-block mt-1">
                                        * Upload file dengan ekstensi .pdf, .jpg, atau .png
                                    </small>
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

            <!-- Script untuk tambah / hapus field -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const wrapper = document.getElementById('file-upload-wrapper');
                    const addButton = document.getElementById('add-file-field');

                    addButton.addEventListener('click', function() {
                        const newField = document.createElement('div');
                        newField.classList.add('input-group', 'mb-2');
                        newField.innerHTML = `
                            <input type="file" class="form-control" name="dokumen[]" accept=".pdf,.jpg,.jpeg,.png">
                            <button type="button" class="btn btn-danger remove-field">Hapus</button>
                        `;
                        wrapper.appendChild(newField);
                    });

                    wrapper.addEventListener('click', function(e) {
                        if (e.target.classList.contains('remove-field')) {
                            e.target.closest('.input-group').remove();
                        }
                    });
                });
            </script>
        </div>
    </div>
</div>

@endsection
@section('js')
@endsection