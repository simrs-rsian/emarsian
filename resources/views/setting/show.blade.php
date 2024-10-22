@extends('includeView.layout')
@section('content')

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <p class="card-description"><h3>Informasi Akun</h3></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Lengkap User</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $userset->fullname }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $userset->username }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Role</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $userset->nama_role }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-warning btn-md" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $userset->id }}">
                        Edit
                    </button>
                </div>
            </div>

            <!-- Modal edit akun -->
            <div class="modal fade" id="editUserModal{{ $userset->id }}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('user.update', $userset->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="fullname">Nama User Pengguna</label>
                                    <input type="text" name="fullname" class="form-control" value="{{ $userset->fullname }}" required>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" class="form-control" value="{{ $userset->username }}" required>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" value="{{ $userset->password }}" required>
                                    <small>*Abaikan jika jika password tidak diubah</small>
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
        </div>
    </div>
</div>
@endsection