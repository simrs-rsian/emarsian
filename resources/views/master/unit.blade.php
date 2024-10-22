@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Jabatan Pegawai</h4>
                    <p class="card-description">
                        <button type="button" class="btn btn-sm btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createUnitModal">
                            TAMBAH DATA
                        </button>
                    </p>
                    <div class="table-responsive pt-3">
                        <table id="dataTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID Jabatan</th>
                                    <th>Nama Jabatan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $key => $unit)
                                    <tr>
                                        <td>{{ $unit->id }}</td>
                                        <td>{{ $unit->nama_unit }}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUnitModal{{ $unit->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('unit.destroy', $unit->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editUnitModal{{ $unit->id }}" tabindex="-1" aria-labelledby="editUnitModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editUnitModalLabel">Edit Unit</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('unit.update', $unit->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="nama_unit">Nama Unit</label>
                                                            <input type="text" name="nama_unit" class="form-control" value="{{ $unit->nama_unit }}" required>
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
<div class="modal fade" id="createUnitModal" tabindex="-1" aria-labelledby="createUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUnitModalLabel">Tambah Data Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('unit.store') }}" method="POST" id="unitForm">
                @csrf
                <div class="modal-body">
                    <div id="unitFields">
                        <div class="form-group">
                            <label for="nama_unit">Nama Unit</label>
                            <input type="text" name="nama_unit[]" class="form-control" required>
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
                <label for="nama_unit">Nama Unit</label>
                <input type="text" name="nama_unit[]" class="form-control" required>
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
