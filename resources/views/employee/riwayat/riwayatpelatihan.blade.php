<p class="card-description"><h3>Riwayat Seminar dan Pelatihan</h3></p>
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRiwayatPelatihanModal">Tambah Riwayat Pelatihan</button>
        <div class="table-responsive" data-simplebar>
            <table class="table table-borderless align-middle text-nowrap">
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
                <tbody id="riwayatPelatihanTable">
                    @foreach($pelatihan as $key => $riwayatPelatihan)
                        <tr>
                            <td>{{ $riwayatPelatihan->id }}</td>
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
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRiwayatPelatihanModal{{ $riwayatPelatihan->id }}">
                                    Edit
                                </button>
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
<!-- Edit dan menampilkan gambar dengan cara terpisah -->
@foreach($pelatihan as $key => $riwayatPelatihan)
<!-- Edit Riwayat Pelatihan -->
<div class="modal fade" id="editRiwayatPelatihanModal{{ $riwayatPelatihan->id }}" tabindex="-1" aria-labelledby="editRiwayatPelatihanModalLabel{{ $riwayatPelatihan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRiwayatPelatihanModalLabel{{ $riwayatPelatihan->id }}">Edit Riwayat Pendidikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('riwayat_pelatihan.update', $riwayatPelatihan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id_employee" value="{{ $riwayatPelatihan->id_employee }}">
                    <input type="hidden" name="riwayat_id" value="{{ $riwayatPelatihan->id }}">
                    
                    <div class="mb-3">
                        <label for="nama_pelatihan{{ $riwayatPelatihan->id }}" class="form-label">Nama Pelatihan</label>
                        <select class="form-select select2-keluarga" style="color: black;"   name="id_pelatihan" required>
                            <option value="">Pilih Nama Pelatihan</option>
                            @foreach($pelatihans as $pelatihan)
                                <option value="{{ $pelatihan->id }}" {{ $riwayatPelatihan->id_pelatihan == $pelatihan->id ? 'selected' : '' }}>
                                    {{ $pelatihan->nama_pelatihan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dokumen{{ $riwayatPelatihan->id }}" class="form-label">Dokumen</label>
                        <input type="file" class="form-control" id="dokumen{{ $riwayatPelatihan->id }}" name="dokumen">
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
<div class="modal fade" id="viewDokumenPelatihanModal{{ $riwayatPelatihan->id }}" tabindex="-1" aria-labelledby="viewDokumenModalPelatihanLabel{{ $riwayatPelatihan->id }}" aria-hidden="true">
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

<!-- Modal Tambah riwayat Pendidikan -->
<div class="modal fade" id="createRiwayatPelatihanModal" tabindex="-1" aria-labelledby="createRiwayatPelatihanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRiwayatPelatihanModalLabel">Tambah Riwayat Pendidikan</h5>
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
