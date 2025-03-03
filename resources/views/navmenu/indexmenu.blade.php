@extends('includeView.layout')
@section('css')
<style>
    .hidden {
        display: none;
    }
</style>
@endsection
@section('content')

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="content-wrapper">
    <div class="row">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Manajemen Hak Akses Menu</h3>
                        <p class="text-subtitle text-muted">Pilih Role dan Atur Hak Akses Menu</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Manajemen Hak Akses Menu</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <p class="card-description">
                            <button type="button" class="btn btn-sm btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createNavmenuModal">
                                TAMBAH REGISTER NAVMENU
                            </button>
                        </p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Link</th>
                                    <th>Icon</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mainmenus as $mainmenu)
                                <tr>
                                    <td>
                                        <!-- Cek jika menu id bukan 1 atau 18, baru tampilkan tombol dropdown -->
                                        @if ($mainmenu->m_id != 1 && $mainmenu->m_id != 18)
                                            <button class="btn btn-sm btn-secondary toggle-submenu" data-toggle="collapse" data-target="#submenu-{{ $mainmenu->m_id }}">
                                                ▼
                                            </button>
                                        @endif
                                        {{ $mainmenu->m_name }}
                                    </td>
                                    <td>-</td>
                                    <td>{{ $mainmenu->m_icon ?? '-' }}</td>
                                    <td>{{ $mainmenu->m_order ?? '-' }}</td>
                                    <td>
                                        @if ($mainmenu->m_status == 1)
                                            Aktif
                                        @elseif ($mainmenu->m_status == 2)
                                            Data Cons
                                        @else
                                            Non Aktif
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-sm btn-warning edit-button" data-id="{{ $mainmenu->m_id }}" data-name="{{ $mainmenu->m_name }}" data-link="{{ $mainmenu->m_link }}" data-icon="{{ $mainmenu->m_icon }}" data-order="{{ $mainmenu->m_order }}" data-status="{{ $mainmenu->m_status }}">Edit</button>
                                        <form action="{{ route('navmenu.destroy', $mainmenu->m_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda Yakin?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Submenu Dropdown (ditampilkan saat tombol ditekan) -->
                                <tr class="collapse" id="submenu-{{ $mainmenu->m_id }}">
                                    <td colspan="6">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Submenu</th>
                                                    <th>Link</th>
                                                    <th>Icon</th>
                                                    <th>Order</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($submenus->where('m_child', $mainmenu->m_id) as $submenu)
                                                <tr>
                                                    <td>
                                                        <!-- Cek jika submenu id bukan 1 atau 18, baru tampilkan tombol dropdown -->
                                                        @if ($submenu->m_id != 1 && $submenu->m_id != 18)
                                                            <button class="btn btn-sm btn-secondary toggle-submenu" data-toggle="collapse" data-target="#consts-{{ $submenu->m_id }}">
                                                                ▼
                                                            </button>
                                                        @endif
                                                        {{ $submenu->m_name }}
                                                    </td>
                                                    <td>{{ $submenu->m_link ?? '-' }}</td>
                                                    <td>{{ $submenu->m_icon ?? '-' }}</td>
                                                    <td>{{ $submenu->m_order ?? '-' }}</td>
                                                    <td>
                                                        @if ($submenu->m_status == 1)
                                                            Aktif
                                                        @elseif ($submenu->m_status == 2)
                                                            Data Cons
                                                        @else
                                                            Non Aktif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <!-- Tombol Edit -->
                                                        <button class="btn btn-sm btn-warning edit-button" data-id="{{ $submenu->m_id }}" data-name="{{ $submenu->m_name }}" data-link="{{ $submenu->m_link }}" data-icon="{{ $submenu->m_icon }}" data-order="{{ $submenu->m_order }}" data-status="{{ $submenu->m_status }}">Edit</button>
                                                        <form action="{{ route('navmenu.destroy', $submenu->m_id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda Yakin?')">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                <!-- Sub-Submenu Dropdown (ditampilkan saat tombol ditekan) -->
                                                <tr class="collapse" id="consts-{{ $submenu->m_id }}">
                                                    <td colspan="6">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Const</th>
                                                                    <th>Link</th>
                                                                    <th>Icon</th>
                                                                    <th>Order</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($submenus->where('m_child', $submenu->m_id) as $const)
                                                                <tr>
                                                                    <td>{{ $const->m_name }}</td>
                                                                    <td>{{ $const->m_link ?? '-' }}</td>
                                                                    <td>{{ $const->m_icon ?? '-' }}</td>
                                                                    <td>{{ $const->m_order ?? '-' }}</td>
                                                                    <td>
                                                                        @if ($const->m_status == 1)
                                                                            Aktif
                                                                        @elseif ($const->m_status == 2)
                                                                            Data Cons
                                                                        @else
                                                                            Non Aktif
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <!-- Tombol Edit -->
                                                                        <button class="btn btn-sm btn-warning edit-button" data-id="{{ $const->m_id }}" data-name="{{ $const->m_name }}" data-link="{{ $const->m_link }}" data-icon="{{ $const->m_icon }}" data-order="{{ $const->m_order }}" data-status="{{ $const->m_status }}">Edit</button>
                                                                        <form action="{{ route('navmenu.destroy', $const->m_id) }}" method="POST" class="d-inline">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda Yakin?')">Delete</button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Menu</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form untuk Edit -->
                                        <form id="editForm" action="{{ route('navmenu.update') }}" method="POST">
                                            @csrf
                                            <input type="text" id="editMenuId">
                                            <div class="form-group">
                                                <label for="editName">Menu Name</label>
                                                <input type="text" class="form-control" id="editName" name="m_name">
                                            </div>
                                            <div class="form-group">
                                                <label for="editLink">Link</label>
                                                <input type="text" class="form-control" id="editLink" name="m_link">
                                            </div>
                                            <div class="form-group">
                                                <label for="editIcon">Icon</label>
                                                <input type="text" class="form-control" id="editIcon" name="m_icon">
                                            </div>
                                            <div class="form-group">
                                                <label for="editOrder">Order</label>
                                                <input type="number" class="form-control" id="editOrder" name="m_order">
                                            </div>
                                            <div class="form-group">
                                                <label for="editStatus">Status</label>
                                                <select class="form-control" id="editStatus" name="m_status">
                                                    <option value="1">Aktif</option>
                                                    <option value="2">Data Cons</option>
                                                    <option value="0">Non Aktif</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- Create Modal -->
<div class="modal fade" id="createNavmenuModal" tabindex="-1" aria-labelledby="createNavmenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createNavmenuModalLabel">Register Navmenu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('navmenu.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="golonganFields">
                        <div class="form-group">
                            <label for="m_name">Nama Menu</label>
                            <input type="text" name="m_name" class="form-control" required>
                            <small style="color: red;">* Jika ini merupakan menu yang tidak perlu diakses langsung (Bukan menu utama/ Submenu), maka input dengan nama <b>cons</b>.</small>
                        </div>
                        <div class="form-group">
                            <label for="m_link">Link Menu</label>
                            <input type="text" name="m_link" class="form-control" required placeholder="navmenu/nama_menu">
                            <small style="color: red;">* Jika ini merupakan <b>menu utama yang didalamnya akan ada submenu</b>, maka input dengan tanda <b>#</b> untuk menjadi menu utama.</small>
                        </div>
                        <div class="form-group">
                            <label for="m_icon">Icon Menu</label>
                            <input type="text" name="m_icon" class="form-control" required placeholder="ti ti-file-analytics fs-7 text-danger">
                            <small style="color: red;">* Jika ini merupakan <b>submenu/ atau menu cons,</b> maka input dengan tanda <b>#</b>.</small>
                        </div>
                        <div class="form-group">
                            <label for="m_child">Chlid Menu</label>
                            <select name="m_child" id="" class="form-select">
                                <option value="0" > - Menu Utama/Baru -</option>
                                @foreach ($mainmenus as $mainmenu)
                                    @if ($mainmenu->m_id != 1 && $mainmenu->m_id != 18)
                                    <option value="{{ $mainmenu->m_id }}">{{ $mainmenu->m_name }}</option>
                                    @endif
                                @endforeach
                                @foreach ($submenus as $submenu)
                                    @if ($submenu->m_id != 1 && $submenu->m_id != 18 && $submenu->m_name != 'cons')
                                    <option value="{{ $submenu->m_id }}">{{ $submenu->m_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <small style="color: red;">* Jika ini merupakan <b>Menu Utama Baru,</b> maka pilih <b>Menu Utama</b>.</small>
                        </div>
                        <div class="form-group">
                            <label for="m_order">Order Menu</label>
                            <input type="number" name="m_order" class="form-control" required>
                            <small style="color: red;">* Jika ini merupakan <b>Menu Utama Baru,</b> maka input dengan angka <b>0</b>.</small>
                        </div>
                        <div class="form-group">
                            <label for="m_order">Status Menu</label>
                            <select name="m_status" id="" class="form-select">
                                <option value="1" selected> Aktif</option>
                                <option value="0">Tidak Aktif</option>
                                <option value="2">Cons</option>
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
@section('js')
<script>
// Tombol dropdown untuk submenu
document.querySelectorAll('.toggle-submenu').forEach(button => {
    button.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        const targetElement = document.querySelector(targetId);

        // Toggle kelas collapse untuk menampilkan atau menyembunyikan submenu
        if (targetElement.classList.contains('collapse')) {
            targetElement.classList.remove('collapse');
        } else {
            targetElement.classList.add('collapse');
        }
    });
});

// Mengisi form modal dengan data dari tombol edit
document.querySelectorAll('.edit-button').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const link = this.dataset.link;
        const icon = this.dataset.icon;
        const order = this.dataset.order;
        const status = this.dataset.status;

        // Set nilai form di dalam modal
        // document.getElementById('editForm').action = `{{ url('navmenu/update') }}/${id}`;
        document.getElementById('editMenuId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editLink').value = link;
        document.getElementById('editIcon').value = icon;
        document.getElementById('editOrder').value = order;
        document.getElementById('editStatus').value = status;

        // Tampilkan modal
        $('#editModal').modal('show');
    });
});

</script>
@endsection