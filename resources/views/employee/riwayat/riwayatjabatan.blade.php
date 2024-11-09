<p class="card-description"><h3>Riwayat Jabatan Pegawai</h3></p>
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRiwayatJabatanModal">Tambah Riwayat Jabatan</button>
        <div class="table-responsive" data-simplebar>
            <table class="table table-borderless align-middle text-nowrap">
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
                <tbody id="riwayatPendidikanTable">
                    @foreach($jabatan as $key => $riwayatJabatan)
                        <tr>
                            <td>{{ $riwayatJabatan->id }}</td>
                            <td>{{ $riwayatJabatan->tahun_mulai }}</td>
                            <td>{{ $riwayatJabatan->tahun_selesai }}</td>
                            <td>
                                @if($riwayatJabatan->dokumen)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenJabatanModal{{ $riwayatJabatan->id }}">
                                        Tampilalkan Dokumen
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $riwayatJabatan->keterangan }}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRiwayatJabatanModal{{ $riwayatJabatan->id }}">
                                    Edit
                                </button>
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
<!-- Edit dan menampilkan gambar dengan cara terpisah -->
@foreach($jabatan as $key => $riwayatJabatan)
<!-- Edit Riwayat Jabatan -->
<div class="modal fade" id="editRiwayatJabatanModal{{ $riwayatJabatan->id }}" tabindex="-1" aria-labelledby="editRiwayatJabatanModalLabel{{ $riwayatJabatan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRiwayatJabatanModalLabel{{ $riwayatJabatan->id }}">Edit Riwayat Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('riwayat_jabatan.update', $riwayatJabatan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id_employee" value="{{ $riwayatJabatan->id_employee }}">
                    <input type="hidden" name="riwayat_id" value="{{ $riwayatJabatan->id }}">

                    <div class="mb-3">
                        <label for="tahun_mulai{{ $riwayatJabatan->id }}" class="form-label">Tahun Masuk</label>
                        <input type="text" class="form-control" id="tahun_mulai{{ $riwayatJabatan->id }}" name="tahun_mulai" value="{{ $riwayatJabatan->tahun_mulai }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_selesai{{ $riwayatJabatan->id }}" class="form-label">Tahun Lulus</label>
                        <input type="text" class="form-control" id="tahun_selesai{{ $riwayatJabatan->id }}" name="tahun_selesai" value="{{ $riwayatJabatan->tahun_selesai }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan{{ $riwayatJabatan->id }}" class="form-label">keterangan/Universitas</label>
                        <input type="text" class="form-control" id="keterangan{{ $riwayatJabatan->id }}" name="keterangan" value="{{ $riwayatJabatan->keterangan }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="dokumen{{ $riwayatJabatan->id }}" class="form-label">Dokumen</label>
                        <input type="file" class="form-control" id="dokumen{{ $riwayatJabatan->id }}" name="dokumen">
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
<div class="modal fade" id="viewDokumenJabatanModal{{ $riwayatJabatan->id }}" tabindex="-1" aria-labelledby="viewDokumenModalJabatanLabel{{ $riwayatJabatan->id }}" aria-hidden="true">
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