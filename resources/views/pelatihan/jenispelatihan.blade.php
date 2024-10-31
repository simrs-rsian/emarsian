@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Jenis Pelatihan</h4>
                    <p class="card-description">
                        <button type="button" class="btn btn-sm btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createJenisPelatihanModal">
                            TAMBAH DATA
                        </button>
                    </p>
                    <div class="table-responsive pt-3">
                        <table id="dataTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID Jenis</th>
                                    <th>Nama Jenis Pelatihan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jenisPelatihans as $key => $jenispelatihan)
                                    <tr>
                                        <td>{{ $jenispelatihan->id }}</td>
                                        <td>{{ $jenispelatihan->nama_jenis }}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editJenisPelatihanModal{{ $jenispelatihan->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('jenispelatihan.destroy', $jenispelatihan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editJenisPelatihanModal{{ $jenispelatihan->id }}" tabindex="-1" aria-labelledby="editJenisPelatihanModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editJenisPelatihanModalLabel">Edit Unit</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('jenispelatihan.update', $jenispelatihan->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="nama_jenis">Nama Jenis Pelatihan</label>
                                                            <input type="text" name="nama_jenis" class="form-control" value="{{ $jenispelatihan->nama_jenis }}" required>
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
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createJenisPelatihanModalLabel">Tambah Data Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('jenispelatihan.store') }}" method="POST" id="unitForm">
                @csrf
                <div class="modal-body">
                    <div id="unitFields">
                        <div class="form-group">
                            <label for="nama_jenis">Nama Jenis Pelatihan</label>
                            <input type="text" name="nama_jenis[]" class="form-control" required>
                        </div>
                    </div>
                    <button type="button" id="addField" class="btn btn-sm btn-info">Tambah Field</button>
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
