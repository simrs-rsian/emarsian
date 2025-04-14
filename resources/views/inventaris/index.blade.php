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
                        <form action="{{ route('inventaris.cetakQrRuangBulk') }}" method="POST" target="_blank">
                            @csrf
                            <table id="dataTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>No</th>
                                        <th>ID Ruang</th>
                                        <th>Nama Ruang</th>
                                        <th style="width: 200px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $data)
                                        <tr>
                                            <td><input type="checkbox" name="selected_ids[]" value="{{ $data->id_ruang }}"></td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->id_ruang }}</td>
                                            <td>{{ $data->nama_ruang }}</td>
                                            <td>
                                                <a href="{{ route('inventaris.show', $data->id_ruang) }}" class="btn btn-primary btn-sm">Detail</a>
                                                <a href="{{ route('inventaris.cetakQrRuang', $data->id_ruang) }}" target="_blank" class="btn btn-warning btn-sm">Cetak Barcode Ruang</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-success mt-3">Cetak Barcode Terpilih</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan script inisialisasi DataTable setelah halaman siap -->
@endsection
@section('js')
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
@endsection
