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
                        <!-- @if ($message)
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @endif -->

                        @if (isset($datas))
                            @if (isset($datas) && request()->has('kodeQrCode'))
                                <table class="table table-striped" id="">
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
                                <br>
                                @if (!empty($ttd_pj->signature))
                                    <table class="table table-striped text-center">
                                        <tr>
                                            <th class="text-center">Mengetahui, </th>
                                            <th class="text-center">&nbsp;</th>
                                            <th class="text-center">Nganjuk, {{$ttd_pj->tanggal_sign}}</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Kasubag RT</td>
                                            <td class="text-center">Kasubag Logistik Umum</td>
                                            <td class="text-center">Penanggung Jawab</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                {{ $QrKasubagRt }} <br>
                                                (Nita Melina W)
                                            </td>
                                            <td class="text-center">
                                                {{ $QrKasubagLu }} <br>
                                                (Mulia Annisa W)
                                            </td>
                                            <td class="text-center">
                                                    <img src="{{ url('dokumen/signature/inventaris/' . $ttd_pj->signature) }}" alt="Penanggung Jawab" style="width: 100px; height: 100px;">
                                                    <br>
                                                    ( {{ $ttd_pj->nama_sign }} )
                                            </td>
                                        </tr>
                                    </table>                                
                                @else
                                    <div class="alert alert-warning text-center"> 
                                        Belum Ada tanda Tangan PJ Ruangan Silahkan TTD terlebih dahulu melalui Botton berikut ini <br>
                                        @if (empty($ttd_pj->signature))
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#signatureModal">Set Tanda Tangan PJ Ruang</button>
                                        @else
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#signatureModal">Update Tanda Tangan PJ Ruang</button>
                                        @endif
                                        <!-- modal -->
                                        <div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="signatureModalLabel">Tanda Tangan PJ Ruangan</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="no_ruang" id="no_ruang" value="{{ $ruangs->id_ruang }}">
                                                        <label><h5>Nama Penanggung Jawab Ruang</h5></label>
                                                        <input class="form-control" type="text" name="nama_sign" id="nama_sign" required>
                                                        <label><h5>Tanda Tangan</h5></label>
                                                        <canvas id="signature-pad" class="border" style="width: 100%; height: 300px;"></canvas>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button id="clear" class="btn btn-danger">Hapus</button>
                                                        <button id="save" class="btn btn-success">Simpan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning">
                                    Silakan scan QR Code untuk menampilkan data.
                                </div>
                            @endif
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
@section('js')

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    // Fungsi untuk menyesuaikan ukuran canvas
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);

        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;

        canvas.getContext('2d').scale(ratio, ratio);

        signaturePad.clear();
    }

    // Rescale canvas saat modal dibuka
    const modal = document.getElementById('signatureModal');
    modal.addEventListener('shown.bs.modal', () => {
        resizeCanvas();
    });

    // Fungsi untuk menghapus tanda tangan
    document.getElementById('clear').addEventListener('click', () => {
        signaturePad.clear();
    });

    // Fungsi untuk mendapatkan area tanda tangan yang relevan (cropping)
    function getCroppedSignature() {
        const context = canvas.getContext('2d');
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);

        let minX = canvas.width, minY = canvas.height, maxX = 0, maxY = 0;

        for (let y = 0; y < canvas.height; y++) {
            for (let x = 0; x < canvas.width; x++) {
                const index = (y * canvas.width + x) * 4;
                const alpha = imageData.data[index + 3]; // Nilai alpha piksel

                if (alpha > 0) {
                    minX = Math.min(minX, x);
                    minY = Math.min(minY, y);
                    maxX = Math.max(maxX, x);
                    maxY = Math.max(maxY, y);
                }
            }
        }

        const width = maxX - minX;
        const height = maxY - minY;

        // Buat canvas baru untuk area yang dipotong
        const croppedCanvas = document.createElement('canvas');
        croppedCanvas.width = width;
        croppedCanvas.height = height;

        const croppedContext = croppedCanvas.getContext('2d');
        croppedContext.drawImage(canvas, minX, minY, width, height, 0, 0, width, height);

        return croppedCanvas.toDataURL('image/png');
    }

    // Fungsi untuk menyimpan tanda tangan
    document.getElementById('save').addEventListener('click', () => {
        if (signaturePad.isEmpty()) {
            alert('Harap tanda tangan terlebih dahulu!');
            return;
        } else if(document.getElementById('nama_sign').value == '') {
            alert('Harap isi nama saksi terlebih dahulu!');
            return;
        }

        const noRuang = document.getElementById('no_ruang').value;
        const nmSign = document.getElementById('nama_sign').value;

        // Ambil gambar yang sudah dipotong
        const croppedDataUrl = getCroppedSignature();

        fetch('{{ route("inventaris.storeSign") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                signature: croppedDataUrl,
                no_ruang: noRuang,
                nama_sign: nmSign
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Tanda tangan berhasil disimpan.');
                console.log('Tanda tangan berhasil disimpan:', data.file);

                // Tutup modal secara otomatis
                const modal = new bootstrap.Modal(document.getElementById('signatureModal'));
                modal.hide();

                // Refresh halaman
                setTimeout(() => {
                    location.reload();
                }, 500); // Beri sedikit jeda sebelum reload
            } else {
                alert('Terjadi kesalahan: ' + data.message);
                console.error('Error:', data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>
@endsection
