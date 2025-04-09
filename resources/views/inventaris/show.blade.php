@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Barang Ruang {{ $ruangs->nama_ruang }} (Kd. {{ $ruangs->id_ruang }})</h4>
                    <p class="card-description">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </p>
                    <div class="table-responsive pt-3">
                        <a href="{{ route('inventaris.create', $ruangs->id_ruang) }}" class="btn btn-primary btn-sm">Tambah Data</a>
                        <a href="{{ route('inventaris.cetakQrRuang', $ruangs->id_ruang) }}" target="_blank" class="btn btn-info btn-sm">Cetak Barcode Ruang</a>
                        <a href="{{ route('inventaris.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>No. Inventaris</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Asal Barang</th>
                                    <th>Tanggal Pengadaan</th>
                                    <th>Harga</th>
                                    <th>Status Barang</th>
                                    <th>Nama Ruang</th>
                                    <th>No. Rak</th>
                                    <th>No. Box</th>
                                    <th>Merk</th>
                                    <th>Tahun Produksi</th>
                                    <th>Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventaris as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>                                        
                                        <td>
                                            <a href="{{ route('inventaris.cetakQrBarang', str_replace('/', '|', $item->no_inventaris)) }}" class="btn btn-info btn-sm" target="_blank">Print</a>
                                            <!-- <a href="{{ route('inventaris.edit', str_replace('/', '|', $item->no_inventaris)) }}) }}" class="btn btn-warning btn-sm">Edit</a> -->
                                            <form action="{{ route('inventaris.destroy', str_replace('/', '|', $item->no_inventaris)) }}) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                        <td>{{ $item->no_inventaris }}</td>
                                        <td>{{ $item->kode_barang }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ $item->asal_barang }}</td>
                                        <td>{{ $item->tgl_pengadaan }}</td>
                                        <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ $item->status_barang }}</td>
                                        <td>{{ $item->nama_ruang }}</td>
                                        <td>{{ $item->no_rak }}</td>
                                        <td>{{ $item->no_box }}</td>
                                        <td>{{ $item->nama_merk }}</td>
                                        <td>{{ $item->thn_produksi }}</td>
                                        <td>{{ $item->nama_kategori }}</td>
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
<script>
    function formatRupiah(input) {
        let value = input.value.replace(/\./g, ""); // Hapus titik yang sudah ada
        if (!value) {
            input.value = "";
            return;
        }

        let formatted = new Intl.NumberFormat("id-ID").format(value);
        input.value = formatted;
    }
</script>

@endsection
