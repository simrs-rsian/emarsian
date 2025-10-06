@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Jadwal Presensi</h4>
                    <p class="card-description">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </p>
                    <h4 class="card-title">Filter</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('pegawai.jadwal_presensi') }}" id="filterForm"> 
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="month">Bulan:</label>
                                        <select name="month" class="form-control" id="month">
                                            <option value="">-- Pilih Bulan --</option>
                                            @foreach (range(1, 12) as $month)
                                                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="years">Tahun:</label>
                                        <select name="years" class="form-control" id="years">
                                            <option value="">-- Pilih Tahun --</option>
                                            @php
                                                $currentYear = date('Y');
                                                $startYear = $currentYear - 5;
                                                $endYear = $currentYear + 5;
                                            @endphp
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <option value="{{ $year }}" {{ request('years') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary me-2" id="submitButton">Submit</button>
                                    <button type="button" class="btn btn-light-secondary" id="resetButton">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive pt-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td>Minggu</td>
                                    <td>Senin</td>
                                    <td>Selasa</td>
                                    <td>Rabu</td>
                                    <td>Kamis</td>
                                    <td>Jumat</td>
                                    <td>Sabtu</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($calendar as $week)
                                    <tr>
                                        @foreach ($week as $i => $day)
                                            @if ($day)
                                                <td @if ($i == 0) style="background-color: #ffe6e6; color: red;" @endif>
                                                    <strong>{{ $day['tanggal'] }}</strong><br>
                                                    {{ $day['jadwal'] }}
                                                </td>
                                            @else
                                                <td></td>
                                            @endif
                                        @endforeach
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
