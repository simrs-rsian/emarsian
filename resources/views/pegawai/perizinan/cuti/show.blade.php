@extends('includeView.layout')
@section('content')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <p class="card-description"><h3>Data Cuti Kode {{ $cuti->kode_cuti }}</h3></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a href="{{ route('pegawai.cuti.index', ['employee_id' => $cuti->id_employee]) }}" class="btn btn-danger btn-sm">Kembali</a>
                        <!-- tombol print -->
                        <a href="{{ route('pegawai.cuti.download', ['kodeCuti' => $cuti->kode_cuti]) }}" class="btn btn-success btn-sm" target="_blank">Download File</a>
                    </div>
                    <br>
                    <div class="table-responsive" data-simplebar style="padding: 20px;">

                        <h4 class="text-center fw-bold mb-4">
                            LEMBAR PERMOHONAN CUTI KARYAWAN
                        </h4>

                        <p>
                            Assalamu'alaikum Warohmatullahi Wabarakatuh
                        </p>

                        <p>
                            Yang bertanda tangan di bawah ini:
                        </p>

                        <table class="table table-borderless table-sm" style="width: 70%;">
                            <tr>
                                <td width="30%">Nama</td>
                                <td width="5%">:</td>
                                <td>{{ $cuti->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td>NIP Karyawan</td>
                                <td>:</td>
                                <td>{{ $cuti->nip_karyawan }}</td>
                            </tr>
                            <tr>
                                <td>Unit / Jabatan</td>
                                <td>:</td>
                                <td>{{ $cuti->nama_unit }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>{{ $cuti->alamat_lengkap }}</td>
                            </tr>
                            <tr>
                                <td>Jenis Cuti</td>
                                <td>:</td>
                                <td>{{ $cuti->nama_jenis_cuti }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Permohonan Cuti</td>
                                <td>:</td>
                                <td>
                                    @if(!empty($tanggal_cuti))
                                        <ul class="mb-1">
                                            @foreach($tanggal_cuti as $tgl)
                                                <li>{{ \Carbon\Carbon::parse($tgl)->format('d-m-Y') }}</li>
                                            @endforeach
                                        </ul>
                                        <strong>({{ $cuti->jumlah_hari_cuti }} hari)</strong>
                                    @else
                                        {{ \Carbon\Carbon::parse($cuti->tanggal_mulai_cuti)->format('d-m-Y') }}
                                        s/d
                                        {{ \Carbon\Carbon::parse($cuti->tanggal_selesai_cuti)->format('d-m-Y') }}
                                        <br>
                                        <strong>({{ $cuti->jumlah_hari_cuti }} hari)</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Total Hari Cuti</td>
                                <td>:</td>
                                <td>{{ $cuti->jumlah_hari_cuti }} Hari</td>
                            </tr>
                            <tr>
                                <td>Keperluan Cuti</td>
                                <td>:</td>
                                <td>{{ $cuti->alasan_cuti }}</td>
                            </tr>
                            <tr>
                                <td>Karyawan Pengganti</td>
                                <td>:</td>
                                <td>{{ $cuti->karyawan_pengganti }}</td>
                            </tr>
                            @if($cuti->id_jenis_cuti == 1)
                            <tr>
                                <td>Keterangan Lainnya</td>
                                <td>:</td>
                                <td>
                                    Jumlah Cuti: {{ $cuti->jumlah_cuti }} Hari ({{ $cuti->tahun }}/Periode{{ $cuti->periode }})<br>
                                    Dipergunakan : {{ $cuti->cuti_diambil }} Hari <br>
                                    Sisa Cuti: {{ $cuti->sisa_cuti }} Hari
                                </td>
                            </tr>
                            @endif
                        </table>

                        <p class="mt-4">
                            Demikian permohonan ini saya sampaikan. Atas perhatian Bapak/Ibu, saya ucapkan terima kasih.
                        </p>

                        <p>
                            Wassalamu’alaikum Warohmatullahi Wabarakatuh.
                        </p>

                        <br><br>

                        <table class="table table-borderless text-center">
                            <tr>
                                <td>Mengetahui<br>Ka. Subbag SDI</td>
                                <td>Menyetujui<br>Atasan Langsung</td>
                                <td>Karyawan Pengganti</td>
                                <td>
                                    Nganjuk, {{ \Carbon\Carbon::parse($cuti->created_at)->format('d-m-Y') }}<br>
                                    Pemohon
                                </td>
                                <td>Pencatat</td>
                            </tr>
                            <tr style="height: 80px;">
                                <td>
                                    @if(isset($cuti->ttd_mengetahui))
                                        <img src="{{ asset('assets/signature/cuti/mengetahui/'.$cuti->ttd_mengetahui) }}" style="width: auto; height: 100px;" alt="ttd pengantar">
                                    @endif
                                </td>
                                <td>
                                    @if(isset($cuti->ttd_menyetujui))
                                        <img src="{{ asset('assets/signature/cuti/menyetujui/'.$cuti->ttd_menyetujui) }}" style="width: auto; height: 100px;" alt="ttd menyetujui">
                                        
                                        <form action="{{ route('pegawai.cuti.nullTtd', ['kodeCuti' => $cuti->kode_cuti ]) }}" method="POST" id="menyetujui-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="kode_cuti" value="{{ $cuti->kode_cuti }}">
                                            <input type="hidden" name="ttd_menyetujui" value="">
                                            <input type="hidden" name="menyetujui" value="">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Tanda tangan">
                                                    <i class="ti ti-trash fs-5"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-primary open-modal-btn btn-sm" data-modal-title="TTD Menyetujui Atasan Langsung" data-canvas-id="menyetujui-pad" data-input-name="Nama Yang Menyetujui">
                                        Ttd Menyetujui
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($cuti->ttd_karyawan_pengganti))
                                        <img src="{{ asset('assets/signature/cuti/pengganti/'.$cuti->ttd_karyawan_pengganti) }}" style="width: auto; height: 100px;" alt="ttd karyawan pengganti">
                                        
                                        <form action="{{ route('pegawai.cuti.nullTtd', ['kodeCuti' => $cuti->kode_cuti ]) }}" method="POST" id="pengganti-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="kode_cuti" value="{{ $cuti->kode_cuti }}">
                                            <input type="hidden" name="ttd_karyawan_pengganti" value="">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Tanda tangan">
                                                <i class="ti ti-trash fs-5"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-primary open-modal-btn btn-sm" data-modal-title="TTD Karyawan Pengganti" data-canvas-id="pengganti-pad">
                                        Ttd Karyawan Pengganti
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($cuti->ttd_karyawan_pemohon))
                                        <img src="{{ asset('assets/signature/cuti/pemohon/'.$cuti->ttd_karyawan_pemohon) }}" style="width: auto; height: 100px;" alt="ttd karyawan pemohon">
                                        
                                        <form action="{{ route('pegawai.cuti.nullTtd', ['kodeCuti' => $cuti->kode_cuti ]) }}" method="POST" id="pemohon-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="kode_cuti" value="{{ $cuti->kode_cuti }}">
                                            <input type="hidden" name="ttd_karyawan_pemohon" value="">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Tanda tangan">
                                                <i class="ti ti-trash fs-5"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-primary open-modal-btn btn-sm" data-modal-title="TTD Karyawan Pemohon" data-canvas-id="pemohon-pad">
                                        Ttd Pemohon
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($cuti->ttd_pencatat))
                                        <img src="{{ asset('assets/signature/cuti/pencatat/'.$cuti->ttd_pencatat) }}" style="width: auto; height: 100px;" alt="ttd pencatat">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>({{ $cuti->mengetahui ?? '....................' }})</td>
                                <td>({{ $cuti->menyetujui ?? '....................' }})</td>
                                <td>({{ $cuti->karyawan_pengganti ?? '....................' }})</td>
                                <td>({{ $cuti->nama_lengkap ?? '....................' }})</td>
                                <td>({{ $cuti->pencatat ?? '....................' }})</td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="universalModal" tabindex="-1" aria-labelledby="universalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="universalModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="kode_cuti" id="kode_cuti" value="{{ $cuti->kode_cuti }}">
                
                <!-- Input Dinamis jika diperlukan -->
                <div id="dynamic-input-container" style="display: none;">
                    <label id="dynamic-label"></label>
                    <input type="text" id="dynamic-input" class="form-control" placeholder="">
                </div>
                <br>
                <!-- Canvas untuk Tanda Tangan -->
                <div id="canvas-container">
                    <canvas id="dynamic-canvas" class="border" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="clear-canvas" class="btn btn-secondary">Clear</button>
                <button type="button" id="save-canvas" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    let signaturePad;

    document.querySelectorAll('.open-modal-btn').forEach(button => {
        button.addEventListener('click', function() {
            const modalTitle = button.getAttribute('data-modal-title');
            const inputName = button.getAttribute('data-input-name');

            document.getElementById('universalModalLabel').textContent = modalTitle;

            const inputContainer = document.getElementById('dynamic-input-container');
            const inputField = document.getElementById('dynamic-input');

            if (inputName) {
                inputContainer.style.display = 'block';
                document.getElementById('dynamic-label').textContent = inputName;
                inputField.setAttribute('placeholder', inputName);
                inputField.setAttribute('name', inputName.toLowerCase().replace(/\s/g, '_'));
                inputField.value = '';
            }

            const existingCanvas = document.getElementById('dynamic-canvas');
            if (existingCanvas) {
                existingCanvas.remove();
            }

            const newCanvas = document.createElement('canvas');
            newCanvas.id = 'dynamic-canvas';
            newCanvas.className = 'border';
            newCanvas.style.width = '100%';
            newCanvas.style.height = '300px';

            document.getElementById('canvas-container').appendChild(newCanvas);

            signaturePad = new SignaturePad(newCanvas);

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                newCanvas.width = newCanvas.offsetWidth * ratio;
                newCanvas.height = newCanvas.offsetHeight * ratio;

                const context = newCanvas.getContext('2d');
                context.scale(ratio, ratio);

                signaturePad.clear();
            }

            const modal = new bootstrap.Modal(document.getElementById('universalModal'));
            document.getElementById('universalModal').addEventListener('shown.bs.modal', () => {
                resizeCanvas();
            });

            modal.show();
        });
    });

    document.getElementById('clear-canvas').addEventListener('click', () => {
        if (signaturePad) {
            signaturePad.clear();
        }
    });

    document.getElementById('save-canvas').addEventListener('click', function() {
        if (!signaturePad) {
            alert('Signature pad belum terinisialisasi.');
            return;
        }

        if (signaturePad.isEmpty()) {
            alert('Harap tanda tangan terlebih dahulu!');
            return;
        }

        const kodeCuti = document.getElementById('kode_cuti').value;
        const inputName = document.getElementById('dynamic-input').value || '';
        const croppedDataUrl = getCroppedSignature();
        const modalTitle = document.getElementById('universalModalLabel').textContent;

        let route = '';
        let data = {
            kode_cuti: kodeCuti,
            picture: croppedDataUrl
        };

        if (modalTitle === "TTD Menyetujui Atasan Langsung") {
            route = '{{  route("pegawai.cuti.ttdMenyetujui") }}';
            data.menyetujui = inputName;
        } else if (modalTitle === "TTD Karyawan Pemohon") {
            route = '{{  route("pegawai.cuti.ttdPemohon") }}';
        } else if (modalTitle === "TTD Karyawan Pengganti") {
            route = '{{  route("pegawai.cuti.ttdPengganti") }}';
        }

        fetch(route, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Gambar berhasil disimpan.');
                const modal = bootstrap.Modal.getInstance(document.getElementById('universalModal'));
                modal.hide();
                setTimeout(() => location.reload(), 500);
            } else {
                alert('Terjadi kesalahan: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    function getCroppedSignature() {
        const canvas = document.getElementById('dynamic-canvas');
        const context = canvas.getContext('2d');
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);

        let minX = canvas.width, minY = canvas.height, maxX = 0, maxY = 0;

        for (let y = 0; y < canvas.height; y++) {
            for (let x = 0; x < canvas.width; x++) {
                const index = (y * canvas.width + x) * 4;
                if (imageData.data[index + 3] > 0) {
                    minX = Math.min(minX, x);
                    minY = Math.min(minY, y);
                    maxX = Math.max(maxX, x);
                    maxY = Math.max(maxY, y);
                }
            }
        }

        const width = maxX - minX;
        const height = maxY - minY;

        const croppedCanvas = document.createElement('canvas');
        croppedCanvas.width = width;
        croppedCanvas.height = height;
        const croppedContext = croppedCanvas.getContext('2d');

        croppedContext.drawImage(canvas, minX, minY, width, height, 0, 0, width, height);

        return croppedCanvas.toDataURL('image/png');
    }
</script>
@endsection