@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Data Cuti</h4>
                    <p class="card-description">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </p>
                    <div class="table-responsive pt-3">
                        <form action="{{ route('pegawai.cuti.update', $cuti->kode_cuti) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="id_employee" value="{{ $cuti->id_employee }}">
                            <input type="text" name="kode_cuti" value="{{ $cuti->kode_cuti }}" hidden>

                            {{-- JENIS CUTI --}}
                            <div class="mb-3">
                                <label class="form-label">Jenis Cuti</label>
                                <input type="text" class="form-control" id="id_jenis_cuti" name="id_jenis_cuti" value="{{ $cuti->id_jenis_cuti }}" hidden>
                                <input type="text" class="form-control" value="{{ $cuti->nama_jenis_cuti }}" readonly>
                            </div>

                            {{-- MULTI TANGGAL --}}
                            @if (in_array($cuti->id_jenis_cuti, [1,2,4]))
                                <div class="mb-3" id="multiTanggalSection">
                                    <label class="form-label">Tanggal Cuti</label>

                                    <div id="tanggal-cuti-wrapper">
                                        @if (in_array($cuti->id_jenis_cuti, [1,2]))
                                            @foreach ($tanggalCuti as $i => $tgl)
                                                <input type="date" class="form-control tanggal-cuti" name="tanggal_cuti[]" value="{{ $tgl }}">
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @else
                                {{-- RANGE TANGGAL --}}
                                <div class="mb-3" id="rangeTanggalSection">
                                    <label class="form-label">Tanggal Cuti</label>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Dari</label>
                                            <input type="date" class="form-control" id="tanggal_mulai"
                                                name="tanggal_mulai_cuti" value="{{ $cuti->tanggal_mulai_cuti }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Sampai</label>
                                            <input type="date" class="form-control" id="tanggal_selesai"
                                                name="tanggal_selesai_cuti" value="{{ $cuti->tanggal_selesai_cuti }}">
                                        </div>
                                    </div>
                                </div>`
                            @endif

                            {{-- JUMLAH HARI --}}
                            <div class="mb-3">
                                <label class="form-label">Jumlah Hari Cuti</label>
                                <input type="number" class="form-control"
                                    id="jumlah_hari_cuti"
                                    name="jumlah_hari_cuti"
                                    value="{{ $cuti->jumlah_hari_cuti }}"
                                    readonly>
                            </div>

                            {{-- ALASAN CUTI --}}
                            <div class="mb-3">
                                <label for="alasan_cuti" class="form-label">Keperluan Cuti</label>
                                <input type="text" class="form-control" id="alasan_cuti" name="alasan_cuti" value="{{ $cuti->alasan_cuti }}" required>
                            </div>

                            {{-- KARYAWAN PENGGANTI --}}
                            <div class="mb-3">
                                <label for="karyawan_pengganti" class="form-label">Karyawan Pengganti</label>
                                <input type="text" class="form-control" id="karyawan_pengganti" name="karyawan_pengganti" value="{{ $cuti->karyawan_pengganti }}" required>
                            </div>

                            <div class="text-end">
                                <button class="btn btn-primary">Update</button>
                                <!-- buat tombol kembali -->
                                <a href="{{ route('pegawai.cuti.index', ['employee_id' => $cuti->id_employee]) }}" class="btn btn-danger">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const jumlahHariInput = document.getElementById('jumlah_hari_cuti');

    /* =========================
       MULTI TANGGAL
    ========================= */
    function hitungMultiTanggal() {
        const tanggalInputs = document.querySelectorAll('.tanggal-cuti');
        let count = 0;

        tanggalInputs.forEach(input => {
            if (input.value) count++;
        });

        jumlahHariInput.value = count;
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('tanggal-cuti')) {
            hitungMultiTanggal();
        }
    });

    /* =========================
       RANGE TANGGAL
    ========================= */
    const tglMulai = document.getElementById('tanggal_mulai');
    const tglSelesai = document.getElementById('tanggal_selesai');

    function hitungRangeTanggal() {
        if (!tglMulai || !tglSelesai) return;

        if (tglMulai.value && tglSelesai.value) {
            const start = new Date(tglMulai.value);
            const end   = new Date(tglSelesai.value);

            if (end >= start) {
                const diffTime = end - start;
                const diffDays = (diffTime / (1000 * 60 * 60 * 24)) + 1;
                jumlahHariInput.value = diffDays;
            } else {
                jumlahHariInput.value = 0;
            }
        }
    }

    if (tglMulai && tglSelesai) {
        tglMulai.addEventListener('change', hitungRangeTanggal);
        tglSelesai.addEventListener('change', hitungRangeTanggal);
    }

    /* =========================
       AUTO HITUNG SAAT EDIT
    ========================= */
    hitungMultiTanggal();
    hitungRangeTanggal();
});
</script>

@endsection
