@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Default Gaji</h4>
                    <p class="card-description">
                        <button type="button" class="btn btn-sm btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createDefaultGajiModal">
                            TAMBAH DATA
                        </button>
                    </p>
                    <div class="table-responsive pt-3">
                        <table id="dataTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Nama Gaji Default</th>
                                    <th>Mode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($defaultGajis as $key => $defaultGaji)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $defaultGaji->id }}</td>
                                        <td>{{ $defaultGaji->gaji_nama }}</td>
                                        <td>{{ $defaultGaji->mode->mode_nama }}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDefaultGajiModal{{ $defaultGaji->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('default_gaji.destroy', $defaultGaji->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editDefaultGajiModal{{ $defaultGaji->id }}" tabindex="-1" aria-labelledby="editDefaultGajiModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editDefaultGajiModalLabel">Edit Golongan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('default_gaji.update', $defaultGaji->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="gaji_nama">Nama Golongan</label>
                                                            <input type="text" name="gaji_nama" class="form-control" value="{{ $defaultGaji->gaji_nama }}" required>
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
<div class="modal fade" id="createDefaultGajiModal" tabindex="-1" aria-labelledby="createDefaultGajiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDefaultGajiModalLabel">Tambah Data Default Gaji</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('default_gaji.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="golonganFields">
                        <div class="form-group">
                            <label for="gaji_nama">Nama Data Gaji</label>
                            <input type="text" name="gaji_nama[]" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="mode_id">Mode</label>
                            <select name="mode_id[]" class="form-control" required>
                                <option value=""> -- Pilih Mode -- </option>
                                @foreach($modeGajis as $gajiMode)
                                    <option value="{{ $gajiMode->id }}">{{ $gajiMode->mode_nama }}</option>
                                @endforeach
                            </select>
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
        const golonganFields = document.getElementById('golonganFields');
        const addFieldButton = document.getElementById('addField');

        addFieldButton.addEventListener('click', function () {
            const newField = document.createElement('div');
            newField.classList.add('form-group', 'mt-2');
            newField.innerHTML = `
                <label for="gaji_nama">Nama Data Gaji</label>
                <input type="text" name="gaji_nama[]" class="form-control" required>
                <label for="mode_id">Mode</label>
                <select name="mode_id[]" class="form-control" required>
                    <option value=""> -- Pilih Mode -- </option>
                    @foreach($modeGajis as $gajiMode)
                        <option value="{{ $gajiMode->id }}">{{ $gajiMode->mode_nama }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-danger btn-sm mt-1 removeField">Remove</button>
            `;
            golonganFields.appendChild(newField);

            // Add event listener to the newly created remove button
            newField.querySelector('.removeField').addEventListener('click', function () {
                golonganFields.removeChild(newField);
            });
        });
    });
</script>

@endsection
