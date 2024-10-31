@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Pelatihan</h4>
                    <p class="card-description">
                        <button type="button" class="btn btn-sm btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createJenisPelatihanModal">
                            TAMBAH DATA
                        </button>
                    </p>
                    <div class="table-responsive pt-3">
                        <table id="dataTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelatihan</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Penyelenggara</th>
                                    <th>Lokasi</th>
                                    <th>Poin/Jam</th>
                                    <th>Jenis</th>
                                    <th>Peserta</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pelatihans as $key => $pelatihan)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $pelatihan->nama_pelatihan }}</td>
                                        <td>{{ $pelatihan->tanggal_mulai }}</td>
                                        <td>{{ $pelatihan->tanggal_selesai }}</td>
                                        <td>{{ $pelatihan->penyelenggara }}</td>
                                        <td>{{ $pelatihan->lokasi }}</td>
                                        <td>{{ $pelatihan->poin }}</td>
                                        <td>{{ $pelatihan->nama_jenis }}</td>
                                        <td>
                                            <a href="{{ route('riwayat_pelatihan.show', $pelatihan->id) }}" class="btn btn-success btn-sm">Input Peserta</a>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPelatihanModal{{ $pelatihan->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('pelatihan.destroy', $pelatihan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editPelatihanModal{{ $pelatihan->id }}" tabindex="-1" aria-labelledby="editPelatihanModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editPelatihanModalLabel">Edit Unit</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('pelatihan.update', $pelatihan->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label class="form-label">Nama Pelatihan</label>
                                                            <input type="text" name="nama_pelatihan" class="form-control" value="{{ $pelatihan->nama_pelatihan }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Tanggal Mulai</label>
                                                            <input type="date" name="tanggal_mulai" class="form-control" value="{{ $pelatihan->tanggal_mulai }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Tanggal Selesai</label>
                                                            <input type="date" name="tanggal_selesai" class="form-control" value="{{ $pelatihan->tanggal_selesai }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Penyelenggara</label>
                                                            <input type="text" class="form-control" id="penyelenggara{{ $pelatihan->id }}" name="penyelenggara" value="{{ $pelatihan->penyelenggara }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Lokasi</label>
                                                            <input type="text" class="form-control" id="lokasi{{ $pelatihan->id }}" name="lokasi" value="{{ $pelatihan->lokasi }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Poin/Jam</label>
                                                            <input type="text" class="form-control" id="poin{{ $pelatihan->id }}" name="poin" value="{{ $pelatihan->poin }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Pilih Jenis Pelatihan</label>
                                                            <select class="form-select select2-profesi" style="color: black;" name="jenis_pelatihan_id">
                                                                <option value="">Pilih Jenis</option>
                                                                @foreach($jenispelatihans as $jenispelatihan)
                                                                    <option value="{{ $jenispelatihan->id }}" {{ $pelatihan->jenis_pelatihan_id == $jenispelatihan->id ? 'selected' : '' }}>
                                                                        {{ $jenispelatihan->nama_jenis }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createJenisPelatihanModal" tabindex="-1" aria-labelledby="createJenisPelatihanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createJenisPelatihanModalLabel">Tambah Data Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pelatihan.store') }}" method="POST" id="unitForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Pelatihan</label>
                        <input type="text" name="nama_pelatihan" class="form-control"  required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Penyelenggara</label>
                        <input type="text" class="form-control" name="penyelenggara" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lokasi</label>
                        <input type="text" class="form-control" name="lokasi" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Poin/Jam</label>
                        <input type="text" class="form-control" name="poin" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pilih Jenis Pelatihan</label>
                        <select class="form-select select2-profesi" style="color: black;" name="jenis_pelatihan_id">
                            <option value="">Pilih Jenis</option>
                            @foreach($jenispelatihans as $jenispelatihan)
                                <option value="{{ $jenispelatihan->id }}">
                                    {{ $jenispelatihan->nama_jenis }}
                                </option>
                            @endforeach
                        </select>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const unitFields = document.getElementById('unitFields');
        const addFieldButton = document.getElementById('addField');

        addFieldButton.addEventListener('click', function () {
            const newField = document.createElement('div');
            newField.classList.add('form-group', 'mt-2');
            newField.innerHTML = `
                <label for="nama_jenis">Nama Unit</label>
                <input type="text" name="nama_jenis[]" class="form-control" required>
                <button type="button" class="btn btn-danger btn-sm mt-1 removeField">Remove</button>
            `;
            unitFields.appendChild(newField);

            // Add event listener to the newly created remove button
            newField.querySelector('.removeField').addEventListener('click', function () {
                unitFields.removeChild(newField);
            });
        });
    });
</script>

@endsection
