@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Check Data Inventaris Ruangan/ Barang</h4>
                    <p class="card-description">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </p>

                    {{-- Scanner QR --}}
                    <div id="reader" style="width: 300px;"></div>
                    <div id="scan-result" class="mt-3"></div>

                    {{-- Data hasil pencarian --}}
                    <div id="data-barang" class="table-responsive pt-3">
                        @if ($message)
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @endif

                        @if (isset($datas))
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
                                    @foreach ($datas as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <a href="{{ route('inventaris.cetakQrBarang', str_replace('/', '|', $item->no_inventaris)) }}" class="btn btn-info btn-sm" target="_blank">Print</a>
                                                <a href="{{ route('inventaris.edit', str_replace('/', '|', $item->no_inventaris)) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('inventaris.destroy', str_replace('/', '|', $item->no_inventaris)) }}" method="POST" style="display:inline;">
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
                        @else
                            <div class="alert alert-warning">
                                Tidak ada data yang ditemukan.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('scan-result').innerText = `Hasil Scan: ${decodedText}`;

        // Redirect ke halaman ini dengan parameter hasil scan
        window.location.href = `{{ url('inventaris/indexChecker') }}?kodeQrCode=${encodeURIComponent(decodedText)}`;
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 250 });

    html5QrcodeScanner.render(onScanSuccess);
</script>

@endsection
