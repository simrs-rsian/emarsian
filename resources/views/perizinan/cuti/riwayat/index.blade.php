@extends('includeView.layout')
@section('content')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
          <!-- buat session edit berhasil atau error -->
            @if(session('success'))
              <div class="alert alert-success show" id="alert-session-success">
                {{ session('success') }}
              </div>
            @elseif(session('error'))
              <div class="alert alert-danger show" id="alert-session-error">
                {{ session('error') }}
              </div>
            @endif

            <script>
              setTimeout(function() {
              let alertSuccess = document.getElementById('alert-session-success');
              let alertError = document.getElementById('alert-session-error');
              if(alertSuccess) {
                alertSuccess.classList.remove('show');
                alertSuccess.classList.add('hide');
              }
              if(alertError) {
                alertError.classList.remove('show');
                alertError.classList.add('hide');
              }
              }, 3000);
            </script>
            <p class="card-description"><h3>Data Riwayat Cuti {{ $employee->nama_lengkap }} (NIP : {{ $employee->nip_karyawan }})</h3></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <a href="{{ route('perizinan.cuti.pegawai.index') }}" class="btn btn-danger btn-sm">Kembali</a>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createCutiModal">Buat Cuti</button>
                    </div>
                    <br>
                    <div class="table-responsive" data-simplebar>
                        <table class="table table-borderless align-middle text-nowrap" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">Aksi</th>
                                    <th scope="col">Kode Cuti</th>
                                    <th scope="col">Tanggal Cuti</th>
                                    <th scope="col">Jenis Cuti</th>
                                    <th scope="col">Alasan Cuti</th>
                                    <th scope="col">Tahun (Periode)</th>
                                </tr>
                            </thead>
                            <tbody id="tabel">
                                @foreach ($riwayatCuti as $cuti)
                                    <tr>
                                        <td>
                                            <!-- Tampilkan Data surat cuti -->
                                            <a href="{{ route('perizinan.riwayat.cuti.show', $cuti->kode_cuti) }}" class="btn btn-info btn-sm">Detail</a>
                                            <!-- edit data -->
                                            <a href="{{ route('perizinan.riwayat.cuti.edit', $cuti->kode_cuti) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <!-- hapus data destroy -->
                                            <form action="{{ route('perizinan.riwayat.cuti.destroy', $cuti->kode_cuti) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                            </form>
                                        </td>
                                        <td>{{ $cuti->kode_cuti }}</td>
                                        <td>
                                            @if(!empty($tanggal_cuti))
                                                @php
                                                    $filteredTanggal = collect($tanggal_cuti)
                                                        ->where('kode_cuti', $cuti->kode_cuti);
                                                @endphp

                                                @if($filteredTanggal->isNotEmpty())
                                                    @foreach($filteredTanggal as $tanggal)
                                                        {{ date('d-m-Y', strtotime($tanggal->tanggal_cuti)) }}
                                                        @if(!$loop->last)<br>@endif
                                                    @endforeach
                                                    <br>
                                                    ( {{ $cuti->jumlah_hari_cuti }} hari )
                                                @else
                                                    {{ date('d-m-Y', strtotime($cuti->tanggal_mulai_cuti)) }}
                                                    sampai
                                                    {{ date('d-m-Y', strtotime($cuti->tanggal_selesai_cuti)) }}
                                                    <br>
                                                    ( {{ $cuti->jumlah_hari_cuti }} hari )
                                                @endif
                                            @else
                                                {{ date('d m Y', strtotime($cuti->tanggal_mulai_cuti)) }}
                                                sampai
                                                {{ date('d m Y', strtotime($cuti->tanggal_selesai_cuti)) }}
                                            @endif
                                        </td>
                                        <td>{{ $cuti->nama_jenis_cuti }}</td>
                                        <td>{{ $cuti->alasan_cuti }}</td>
                                        <td>
                                            @if($cuti->id_jenis_cuti == 1)
                                                {{ $cuti->tahun }} (Periode {{ $cuti->periode }})
                                            @else
                                                -
                                            @endif
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

<!-- Modal Tambah Data Cuti -->
<div class="modal fade" id="createCutiModal" tabindex="-1" aria-labelledby="createCutiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCutiModalLabel">Tambah Data Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('perizinan.riwayat.cuti.store') }}" method="POST" enctype="multipart/form-data"> 
                @csrf
                <div class="modal-body">
                    <!-- Input ID Pegawai yang di-hidden -->
                    <input type="hidden" name="id_employee" value="{{ $employee->id }}">
                    <input type="hidden" name="id_employee_cuti" value="{{ $employeeCuti->id }}">

                    <div class="mb-3">
                        <label for="kode_cuti" class="form-label">Kode Cuti</label>
                        <input type="text" class="form-control" id="kode_cuti" name="kode_cuti" value="{{ $kodeCuti }}" readonly required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="jenis_cuti">Jenis Cuti</label>
                        <select class="form-control" id="id_jenis_cuti" name="id_jenis_cuti" required>
                            <option value="">-- Pilih Jenis Cuti --</option>

                            @foreach ($jenisCuti as $cuti)
                                @if ($cuti->id == 1 && $sisaCuti <= 0)
                                    @continue
                                @endif

                                <option value="{{ $cuti->id }}"
                                    data-sisa="{{ $cuti->id == 1 ? $sisaCuti : 0 }}"
                                    {{ $cuti->id == 1 ? 'selected' : '' }}>
                                    
                                    {{ $cuti->nama_jenis_cuti }}
                                    @if ($cuti->id == 1)
                                        (Sisa {{ $sisaCuti }} hari)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="multiTanggalSection">
                        <label class="form-label">Tanggal Cuti</label>

                        <div id="tanggal-cuti-wrapper">
                            <div class="d-flex mb-2 tanggal-cuti-item">
                                <input type="date" class="form-control tanggal-cuti" name="tanggal_cuti[]" >
                                <button type="button" class="btn btn-danger ms-2 remove-tanggal" disabled>-</button>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-success mt-2" id="addTanggal">
                            + Tambah Tanggal
                        </button>
                    </div>

                    <div class="mb-3 d-none" id="rangeTanggalSection">
                        <label class="form-label">Tanggal Cuti</label>

                        <div class="row">
                            <div class="col-md-6">
                                <label>Dari Tanggal</label>
                                <input type="date" class="form-control" id="tanggal_mulai_cuti" name="tanggal_mulai_cuti">
                            </div>
                            <div class="col-md-6">
                                <label>Sampai Tanggal</label>
                                <input type="date" class="form-control" id="tanggal_selesai_cuti" name="tanggal_selesai_cuti">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jumlah Hari Cuti</label>
                        <input type="number" class="form-control" id="jumlah_hari_cuti" 
                            name="jumlah_hari_cuti" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="alasan_cuti" class="form-label">Keperluan Cuti</label>
                        <input type="text" class="form-control" id="alasan_cuti" name="alasan_cuti" required>
                    </div>

                    <div class="mb-3">
                        <label for="karyawan_pengganti" class="form-label">Karyawan Pengganti</label>
                        <input type="text" class="form-control" id="karyawan_pengganti" name="karyawan_pengganti" required>
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
<!-- Javascritp  -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const jenisCuti = document.getElementById('id_jenis_cuti');

    const multiSection = document.getElementById('multiTanggalSection');
    const rangeSection = document.getElementById('rangeTanggalSection');

    const wrapper = document.getElementById('tanggal-cuti-wrapper');
    const addBtn = document.getElementById('addTanggal');

    const jumlahHari = document.getElementById('jumlah_hari_cuti');
    const tanggalMulai = document.getElementById('tanggal_mulai_cuti');
    const tanggalSelesai = document.getElementById('tanggal_selesai_cuti');

    function totalTanggal() {
        return wrapper.querySelectorAll('.tanggal-cuti').length;
    }

    function getMaxTanggal() {
        const opt = jenisCuti.options[jenisCuti.selectedIndex];
        const sisa = parseInt(opt.dataset.sisa);

        if (jenisCuti.value == '1') return sisa; // Tahunan
        if (jenisCuti.value == '2') return 3;    // Menikah
        if (jenisCuti.value == '4') return 2;    // Menikah
        return null;
    }

    function hitungJumlahHariMulti() {
        let count = 0;
        wrapper.querySelectorAll('.tanggal-cuti').forEach(i => {
            if (i.value) count++;
        });
        jumlahHari.value = count;
    }

    function hitungJumlahHariRange() {
        if (tanggalMulai.value && tanggalSelesai.value) {
            const start = new Date(tanggalMulai.value);
            const end = new Date(tanggalSelesai.value);
            const diff = (end - start) / (1000 * 60 * 60 * 24) + 1;

            jumlahHari.value = diff > 0 ? diff : 0;
        }
    }

    addBtn.addEventListener('click', function () {
        const max = getMaxTanggal();

        if (max !== null && totalTanggal() >= max) {
            alert('Jumlah hari cuti melebihi batas');
            return;
        }

        const div = document.createElement('div');
        div.className = 'd-flex mb-2 tanggal-cuti-item';
        div.innerHTML = `
            <input type="date" class="form-control tanggal-cuti" name="tanggal_cuti[]">
            <button type="button" class="btn btn-danger ms-2 remove-tanggal">-</button>
        `;
        wrapper.appendChild(div);
        hitungJumlahHariMulti();
    });

    wrapper.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-tanggal')) {
            e.target.parentElement.remove();
            hitungJumlahHariMulti();
        }
    });

    wrapper.addEventListener('change', hitungJumlahHariMulti);

    tanggalMulai?.addEventListener('change', hitungJumlahHariRange);
    tanggalSelesai?.addEventListener('change', hitungJumlahHariRange);

    jenisCuti.addEventListener('change', function () {

        // Reset
        jumlahHari.value = '';

        if (['1','2', '4'].includes(this.value)) {
            multiSection.classList.remove('d-none');
            rangeSection.classList.add('d-none');
        } 
        else if (['3','5'].includes(this.value)) {
            multiSection.classList.add('d-none');
            rangeSection.classList.remove('d-none');
        }
    });

});
</script>
@endsection