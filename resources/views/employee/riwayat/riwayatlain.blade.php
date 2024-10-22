<p class="card-description"><h3>Riwayat Lain-Lain</h3></p>
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRiwayatLainModal">Tambah Riwayat Lain-Lain</button>
        <div class="table-responsive" data-simplebar>
            <table class="table table-borderless align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Riwayat</th>
                        <th scope="col">Tanggal Riwayat</th>
                        <th scope="col">Dokumen</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody id="riwayatPendidikanTable">
                    @foreach($lain as $key => $riwayatLain)
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
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRiwayatLainModal{{ $riwayatLain->id }}">
                                    Edit
                                </button>
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
<!-- Edit dan menampilkan gambar dengan cara terpisah -->
@foreach($lain as $key => $riwayatLain)
<!-- Edit Riwayat Lain-Lain -->
<div class="modal fade" id="editRiwayatLainModal{{ $riwayatLain->id }}" tabindex="-1" aria-labelledby="editRiwayatLainModalLabel{{ $riwayatLain->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRiwayatLainModalLabel{{ $riwayatLain->id }}">Edit Riwayat Lain-Lain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('riwayat_lain.update', $riwayatLain->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id_employee" value="{{ $riwayatLain->id_employee }}">
                    <input type="hidden" name="riwayat_id" value="{{ $riwayatLain->id }}">

                    <div class="mb-3">
                        <label for="nama_riwayat{{ $riwayatLain->id }}" class="form-label">Nama Riwayat</label>
                        <input type="text" class="form-control" id="nama_riwayat{{ $riwayatLain->id }}" name="nama_riwayat" value="{{ $riwayatLain->nama_riwayat }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_riwayat{{ $riwayatLain->id }}" class="form-label">Tanggal Riwayat</label>
                        <input type="text" class="form-control" id="tanggal_riwayat{{ $riwayatLain->id }}" name="tanggal_riwayat" value="{{ $riwayatLain->tanggal_riwayat }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="dokumen{{ $riwayatLain->id }}" class="form-label">Dokumen</label>
                        <input type="file" class="form-control" id="dokumen{{ $riwayatLain->id }}" name="dokumen">
                        * Silahkan Upload file dengan extention .pdf/.jpg/.png
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <!-- Modal untuk menampilkan gambar dokumen -->
    <div class="modal fade" id="viewDokumenLainModal{{ $riwayatLain->id }}" tabindex="-1" aria-labelledby="viewDokumenLainModalLabel{{ $riwayatLain->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDokumenLainModalLabel{{ $riwayatLain->id }}">Dokumen {{ $riwayatLain->nama_sriwayat }}</h5>
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

<!-- Modal Tambah Riwayat Lain-Lain -->
<div class="modal fade" id="createRiwayatLainModal" tabindex="-1" aria-labelledby="createRiwayatLainModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRiwayatLainModalLabel">Tambah Riwayat Lain-Lain</h5>
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