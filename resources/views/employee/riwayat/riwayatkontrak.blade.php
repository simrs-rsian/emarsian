<p class="card-description"><h3>Riwayat Kontrak</h3></p>
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRiwayatKontrakModal">Tambah Riwayat Kontrak</button>
        <div class="table-responsive" data-simplebar>
            <table class="table table-borderless align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Tanggal Mulai</th>
                        <th scope="col">Tanggal Selesai</th>
                        <th scope="col">Dokumen</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody id="riwayatPendidikanTable">
                    @foreach($kontrak as $key => $riwayatKontrak)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $riwayatKontrak->tanggal_mulai }}</td>
                            <td>{{ $riwayatKontrak->tanggal_selesai }}</td>
                            <td>
                                @if($riwayatKontrak->dokumen)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenKontrakModal{{ $riwayatKontrak->id }}">
                                        Tampilalkan Dokumen
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRiwayatKontrakModal{{ $riwayatKontrak->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('riwayat_kontrak.destroy', $riwayatKontrak->id) }}" method="POST" class="d-inline">
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
@foreach($kontrak as $key => $riwayatKontrak)
<!-- Edit Riwayat Kontrak -->
<div class="modal fade" id="editRiwayatKontrakModal{{ $riwayatKontrak->id }}" tabindex="-1" aria-labelledby="editRiwayatKontrakModalLabel{{ $riwayatKontrak->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRiwayatKontrakModalLabel{{ $riwayatKontrak->id }}">Edit Riwayat Kontrak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('riwayat_kontrak.update', $riwayatKontrak->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id_employee" value="{{ $riwayatKontrak->id_employee }}">
                    <input type="hidden" name="riwayat_id" value="{{ $riwayatKontrak->id }}">

                    <div class="mb-3">
                        <label for="tanggal_mulai{{ $riwayatKontrak->id }}" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai{{ $riwayatKontrak->id }}" name="tanggal_mulai" value="{{ $riwayatKontrak->tanggal_mulai }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_selesai{{ $riwayatKontrak->id }}" class="form-label">Tanggal Selesai</label>
                        <input type="text" class="form-control" id="tanggal_selesai{{ $riwayatKontrak->id }}" name="tanggal_selesai" value="{{ $riwayatKontrak->tanggal_selesai }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="dokumen{{ $riwayatKontrak->id }}" class="form-label">Dokumen</label>
                        <input type="file" class="form-control" id="dokumen{{ $riwayatKontrak->id }}" name="dokumen">
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
    <div class="modal fade" id="viewDokumenKontrakModal{{ $riwayatKontrak->id }}" tabindex="-1" aria-labelledby="viewDokumenKontrakModalLabel{{ $riwayatKontrak->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDokumenKontrakModalLabel{{ $riwayatKontrak->id }}">Dokumen riwayat Kontrak tanggal Akhir {{ $riwayatKontrak->tanggal_selesai }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                @if($riwayatKontrak->dokumen)
                    @php
                        $extension = pathinfo($riwayatKontrak->dokumen, PATHINFO_EXTENSION);
                    @endphp
                    
                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ url($riwayatKontrak->dokumen) }}" alt="Dokumen" class="img-fluid">
                    @elseif($extension == 'pdf')
                        <iframe src="{{ url($riwayatKontrak->dokumen) }}" width="100%" height="500px"></iframe>
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

<!-- Modal Tambah Riwayat Kontrak -->
<div class="modal fade" id="createRiwayatKontrakModal" tabindex="-1" aria-labelledby="createRiwayatKontrakModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRiwayatKontrakModalLabel">Tambah Riwayat Kontrak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('riwayat_kontrak.store') }}" method="POST" enctype="multipart/form-data"> 
                @csrf
                <div class="modal-body">
                    <!-- Input ID Pegawai yang di-hidden -->
                    <input type="hidden" id="id_employee" name="id_employee" value="{{ $employee->id }}">

                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
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