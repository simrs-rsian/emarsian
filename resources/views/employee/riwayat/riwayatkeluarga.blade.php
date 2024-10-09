<p class="card-description"><h3>Riwayat Keluarga</h3></p>
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createRiwayatKeluargaModal">Tambah Riwayat Keluarga</button>
        <div class="table-responsive" data-simplebar>
            <table class="table table-borderless align-middle text-nowrap">
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
                    @foreach($keluarga as $key => $riwayatKeluarga)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $riwayatKeluarga->nama_keluarga }}</td>
                            <td>{{ $riwayatKeluarga->status_keluarga }}</td>
                            <td>{{ $riwayatKeluarga->pekerjaan_keluarga }}</td>
                            <td>{{ $riwayatKeluarga->pendidikan_keluarga }}</td>
                            <td>
                                @if($riwayatKeluarga->dokumen)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenModal{{ $riwayatKeluarga->id }}">
                                        Tampilkan Dokumen
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRiwayatModal{{ $riwayatKeluarga->id }}">
                                    Edit
                                </button>
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

<!-- Edit Riwayat Keluarga Modal -->
@foreach($keluarga as $riwayatKeluarga)
<div class="modal fade" id="editRiwayatModal{{ $riwayatKeluarga->id }}" tabindex="-1" aria-labelledby="editRiwayatModalLabel{{ $riwayatKeluarga->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRiwayatModalLabel{{ $riwayatKeluarga->id }}">Edit Riwayat Keluarga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('riwayat_keluarga.update', $riwayatKeluarga->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id_employee" value="{{ $riwayatKeluarga->id_employee }}">
                    <input type="hidden" name="riwayat_id" value="{{ $riwayatKeluarga->id }}">

                    <div class="mb-3">
                        <label for="nama_keluarga{{ $riwayatKeluarga->id }}" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_keluarga{{ $riwayatKeluarga->id }}" name="nama_keluarga" value="{{ $riwayatKeluarga->nama_keluarga }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status_keluarga{{ $riwayatKeluarga->id }}" class="form-label">Status Keluarga</label>
                        <select name="status_keluarga" id="status_keluarga{{ $riwayatKeluarga->id }}" class="form-control" required>
                            <option value="Bapak" {{ $riwayatKeluarga->status_keluarga == 'Bapak' ? 'selected' : '' }}>Bapak</option>
                            <option value="Ibu" {{ $riwayatKeluarga->status_keluarga == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                            <option value="Anak" {{ $riwayatKeluarga->status_keluarga == 'Anak' ? 'selected' : '' }}>Anak</option>
                            <option value="Kakek" {{ $riwayatKeluarga->status_keluarga == 'Kakek' ? 'selected' : '' }}>Kakek</option>
                            <option value="Nenek" {{ $riwayatKeluarga->status_keluarga == 'Nenek' ? 'selected' : '' }}>Nenek</option>
                            <option value="Lainnya" {{ $riwayatKeluarga->status_keluarga == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="pekerjaan_keluarga{{ $riwayatKeluarga->id }}" class="form-label">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan_keluarga{{ $riwayatKeluarga->id }}" name="pekerjaan_keluarga" value="{{ $riwayatKeluarga->pekerjaan_keluarga }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="pendidikan_keluarga{{ $riwayatKeluarga->id }}" class="form-label">Pendidikan Terakhir</label>
                        <select name="pendidikan_keluarga" id="pendidikan_keluarga{{ $riwayatKeluarga->id }}" class="form-control" required>
                            <option value="SD" {{ $riwayatKeluarga->pendidikan_keluarga == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ $riwayatKeluarga->pendidikan_keluarga == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ $riwayatKeluarga->pendidikan_keluarga == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="Diploma" {{ $riwayatKeluarga->pendidikan_keluarga == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                            <option value="Sarjana" {{ $riwayatKeluarga->pendidikan_keluarga == 'Sarjana' ? 'selected' : '' }}>Sarjana</option>
                            <option value="Lainnya" {{ $riwayatKeluarga->pendidikan_keluarga == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="dokumen{{ $riwayatKeluarga->id }}" class="form-label">Dokumen</label>
                        <input type="file" class="form-control" id="dokumen{{ $riwayatKeluarga->id }}" name="dokumen">
                        <small class="text-muted">* Silahkan Upload file dengan ekstensi .pdf/.jpg/.png</small>
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
@endforeach

<!-- Modal Tambah Riwayat Keluarga -->
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
                        <small class="text-muted">* Silahkan Upload file dengan ekstensi .pdf/.jpg/.png</small>
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
