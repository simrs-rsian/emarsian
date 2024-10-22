@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data User</h4>
                    <p class="card-description">
                        <button type="button" class="btn btn-sm btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                            TAMBAH DATA
                        </button>
                    </p>
                    <div class="table-responsive pt-3">
                        <table id="dataTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID User</th>
                                    <th>Nama User</th>
                                    <th>Role</th>
                                    <th>Username</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $key => $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->fullname }}</td>
                                        <td>{{ $user->nama_role ?? 'No Role' }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('user.update', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="fullname">Nama User Pengguna</label>
                                                            <input type="text" name="fullname" class="form-control" value="{{ $user->fullname }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="username">Username</label>
                                                            <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="password">Password</label>
                                                            <input type="password" name="password" class="form-control" value="{{ $user->password }}" required>
                                                            <small>*Abaikan jika jika password tidak diubah</small>
                                                        </div>
                                                    </div>
                                                    @if($user->role != 1)
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="role">Role</label>
                                                                <select class="form-select" style="color: black;" name="role" required>
                                                                    <option value="">Pilih Role</option>
                                                                    @foreach($roles as $role)
                                                                        <option value="{{ $role->id }}" {{ $user->role == $role->id ? 'selected' : '' }}>
                                                                            {{ $role->nama_role }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="role">Role</label>
                                                                <input type="text" name="role" class="form-control" value="{{ $user->nama_role ?? 'No Role' }}" required disabled>
                                                            </div>
                                                        </div>
                                                    @endif


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
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoleModalLabel">Tambah Data User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.store') }}" method="POST" id="unitForm">
                @csrf
                <div class="modal-body">
                    <div>
                        <div class="form-group">
                            <label for="fullname">Nama Pengguna</label>
                            <input type="text" name="fullname" class="form-control" required>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="fullname">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="fullname">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="fullname">Role</label>
                            <select class="form-select" style="color: black;"   name="role" required>
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">
                                        {{ $role->nama_role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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

@endsection
