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
                        <form action="{{ route('perizinan.cuti.pegawai.update', [
                                $cuti->employee_id,
                                'tahun' => $cuti->tahun,
                                'periode' => $cuti->periode
                            ]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="employee_id">Pegawai</label>
                                <input type="text" class="form-control"
                                    value="{{ $cuti->employee_nip }} - {{ $cuti->employee_name }}"
                                    readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="tahun">Tahun</label>
                                <input type="number" class="form-control"
                                    name="tahun"
                                    value="{{ $cuti->tahun }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="periode">Periode</label>
                                <input type="number" class="form-control"
                                    name="periode"
                                    value="{{ $cuti->periode }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="jumlah_cuti">Jumlah Cuti (Hari)</label>
                                <input type="number" class="form-control"
                                    name="jumlah_cuti"
                                    value="{{ $cuti->jumlah_cuti }}" required>
                            </div>

                            <div class="form-group">
                                <label for="cuti_diambil">Cuti Yang Diambil (Hari)</label>
                                <input type="number" class="form-control"
                                    name="cuti_diambil"
                                    value="{{ $cuti->cuti_diambil }}" required>
                            </div><br>

                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>
                            <!-- tombol kembali -->
                            <a href="{{ route('perizinan.cuti.pegawai.index') }}" class="btn btn-secondary">
                                Kembali
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
