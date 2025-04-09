@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Inventaris</h4>
                    <p class="card-description">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </p>
                    <div class="table-responsive pt-3">
                        <table id="dataTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Ruang</th>
                                    <th>Nama Ruang</th>
                                    <th style="width: 200px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->id_ruang }}</td>
                                        <td>{{ $data->nama_ruang }}</td>
                                        <td>
                                            <a href="{{ route('inventaris.show', $data->id_ruang) }}" class="btn btn-primary btn-sm">Detail</a>
                                            <a href="{{ route('inventaris.cetakQrRuang', $data->id_ruang) }}" target="_blank" class="btn btn-warning btn-sm">Cetak Barcode Ruang</a>
                                            <!-- <form action="{{ route('inventaris.destroy', $data->id_ruang) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form> -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan script inisialisasi DataTable setelah halaman siap -->
@endsection
