@extends('includeView.layout')
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
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="role-select" class="form-label">Pilih Role:</label>
                                <select id="role-select" class="form-control select2">
                                    <option value="" disabled selected>Pilih Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Submenu</th>
                                    <th>Sub-submenu</th>
                                    <th>Akses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mainmenus as $mainmenu)
                                <tr>
                                    <td>{{ $mainmenu->m_name }}</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>
                                        <input type="checkbox" class="menu-checkbox" data-menu-id="{{ $mainmenu->m_id }}" />
                                    </td>
                                </tr>
                                @foreach ($submenus->where('m_child', $mainmenu->m_id) as $submenu)
                                <tr>
                                    <td></td>
                                    <td>{{ $submenu->m_name }}</td>
                                    <td>-</td>
                                    <td>
                                        <input type="checkbox" class="menu-checkbox" data-menu-id="{{ $submenu->m_id }}" />
                                    </td>
                                </tr>
                                @foreach ($subsubmenus->where('m_child', $submenu->m_id) as $subsubmenu)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $subsubmenu->m_name }}</td>
                                    <td>
                                        <input type="checkbox" class="menu-checkbox" data-menu-id="{{ $subsubmenu->m_id }}" />
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
