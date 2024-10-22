<p class="card-description"><h3>Riwayat Pendidikan</h3></p>
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRiwayatPendidikanModal">Tambah Riwayat Pendidikan</button>
        <div class="table-responsive" data-simplebar>
            <table class="table table-borderless align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Tahun Masuk</th>
                        <th scope="col">Tahun Lulus</th>
                        <th scope="col">Sekolah/Universitas</th>
                        <th scope="col">Lokasi</th>
                        <th scope="col">Dokumen</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody id="riwayatPendidikanTable">
                    @foreach($pendidikan as $key => $riwayatPendidikan)
                        <tr>
                            <td>{{ $riwayatPendidikan->id }}</td>
                            <td>{{ $riwayatPendidikan->tahun_masuk }}</td>
                            <td>{{ $riwayatPendidikan->tahun_lulus }}</td>
                            <td>{{ $riwayatPendidikan->nama_sekolah }}</td>
                            <td>{{ $riwayatPendidikan->lokasi }}</td>
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
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRiwayatPendidikanModal{{ $riwayatPendidikan->id }}">
                                    Edit
                                </button>
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
<!-- Edit dan menampilkan gambar dengan cara terpisah -->
@foreach($pendidikan as $key => $riwayatPendidikan)
<!-- Edit Riwayat Pendidikan -->
<div class="modal fade" id="editRiwayatPendidikanModal{{ $riwayatPendidikan->id }}" tabindex="-1" aria-labelledby="editRiwayatPendidikanModalLabel{{ $riwayatPendidikan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRiwayatPendidikanModalLabel{{ $riwayatPendidikan->id }}">Edit Riwayat Pendidikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('riwayat_pendidikan.update', $riwayatPendidikan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id_employee" value="{{ $riwayatPendidikan->id_employee }}">
                    <input type="hidden" name="riwayat_id" value="{{ $riwayatPendidikan->id }}">

                    <div class="mb-3">
                        <label for="tahun_masuk{{ $riwayatPendidikan->id }}" class="form-label">Tahun Masuk</label>
                        <input type="text" class="form-control" id="tahun_masuk{{ $riwayatPendidikan->id }}" name="tahun_masuk" value="{{ $riwayatPendidikan->tahun_masuk }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_lulus{{ $riwayatPendidikan->id }}" class="form-label">Tahun Lulus</label>
                        <input type="text" class="form-control" id="tahun_lulus{{ $riwayatPendidikan->id }}" name="tahun_lulus" value="{{ $riwayatPendidikan->tahun_lulus }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="sekolah{{ $riwayatPendidikan->id }}" class="form-label">Sekolah/Universitas</label>
                        <input type="text" class="form-control" id="sekolah{{ $riwayatPendidikan->id }}" name="sekolah" value="{{ $riwayatPendidikan->nama_sekolah }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="lokasi{{ $riwayatPendidikan->id }}" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi{{ $riwayatPendidikan->id }}" name="lokasi" value="{{ $riwayatPendidikan->lokasi }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="dokumen{{ $riwayatPendidikan->id }}" class="form-label">Dokumen</label>
                        <input type="file" class="form-control" id="dokumen{{ $riwayatPendidikan->id }}" name="dokumen">
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