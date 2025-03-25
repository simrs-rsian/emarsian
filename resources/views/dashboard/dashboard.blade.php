@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-md-between flex-wrap mb-4">
                <div>
                    <p class="mb-2 text-md-center text-lg-left">Data Pegawai Kontrak</p>
                    <h1 class="mb-0">{{ $tetapCount->total_employees; }}</h1>
                </div>
                <i class="typcn typcn-briefcase icon-xl text-secondary"></i>
                </div>
            </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                <div>
                    <p class="mb-2 text-md-center text-lg-left">Data Pegawai Tetap & Capeg</p>
                    <h1 class="mb-0">{{ $kontrakCount->total_employees; }}</h1>
                </div>
                <i class="typcn typcn-chart-pie icon-xl text-secondary"></i>
                </div>
            </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                <div>
                    <p class="mb-2 text-md-center text-lg-left">Total Seluruh Pegawai</p>
                    <h1 class="mb-0">{{ $allCount->total_employees; }}</h1>
                </div>
                <i class="typcn typcn-clipboard icon-xl text-secondary"></i>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body border-bottom">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-2 mb-md-0 text-uppercase fw-medium">Data Berdasarkan Unit/Jabatan</h6>
                    </div>
                    
                    <div class="table-responsive pt-3">
                        <table class="table table-striped project-orders-table">
                            <thead>
                                <tr>
                                    <th class="ms-5">No</th>
                                    <th>Unit/ Jabatan</th>
                                    <th>Total Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalUnits = 0;
                                @endphp
                                @foreach($unitsCount as $index => $unit)
                                    <tr>
                                        <td>{{ $index + 1 }}</td> 
                                        <td>{{ $unit->nama_unit }}</td>
                                        <td>{{ $unit->total_employees }}</td>
                                    </tr>
                                @php
                                    $totalUnits += $unit->total_employees;
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th>{{ $totalUnits }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body border-bottom">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-2 mb-md-0 text-uppercase fw-medium">Data Berdasarkan Kenis Kelamin</h6>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-striped project-orders-table">
                            <thead>
                                <tr>
                                    <th class="ms-5">No</th>
                                    <th>Unit/ Jabatan</th>
                                    <th>Laki-Laki</th>
                                    <th>Perempuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalLakiLaki = 0;
                                    $totalPerempuan = 0;
                                @endphp
                                @foreach($unitsJkCount as $index => $unitJk)
                                    <tr>
                                        <td>{{ $index + 1 }}</td> 
                                        <td>{{ $unitJk->nama_unit }}</td>
                                        <td>{{ $unitJk->total_laki_laki }}</td>
                                        <td>{{ $unitJk->total_perempuan }}</td>
                                    </tr>
                                @php
                                    $totalLakiLaki += $unitJk->total_laki_laki;
                                    $totalPerempuan += $unitJk->total_perempuan;
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th>{{ $totalLakiLaki }}</th>
                                    <th>{{ $totalPerempuan }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body border-bottom">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-2 mb-md-0 text-uppercase fw-medium">Data Berdasarkan Golongan</h6>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-striped project-orders-table">
                        <thead>
                            <tr>
                                <th class="ms-5">No</th>
                                <th>Unit/ Jabatan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                            <tbody>
                                @php
                                    $totalGolongans = 0;
                                @endphp
                                @foreach($golonganCount as $index => $golongan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td> 
                                        <td>{{ $golongan->nama_golongan }}</td>
                                        <td>{{ $golongan->total_golongan }}</td>
                                    </tr>
                                @php
                                    $totalGolongans += $golongan->total_golongan;
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th>{{ $totalGolongans }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body border-bottom">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-2 mb-md-0 text-uppercase fw-medium">Data Berdasarkan Status Karyawan</h6>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-striped project-orders-table">
                        <thead>
                            <tr>
                                <th class="ms-5">No</th>
                                <th>Status Karyawan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                            <tbody>
                                @php
                                    $totalStatuses = 0;
                                @endphp
                                @foreach($statusKaryawanCount as $index => $statusKaryawan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td> 
                                        <td>{{ $statusKaryawan->nama_status }}</td>
                                        <td>{{ $statusKaryawan->total_status_karyawan }}</td>
                                    </tr>
                                @php
                                    $totalStatuses += $statusKaryawan->total_status_karyawan;
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th>{{ $totalStatuses }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body border-bottom">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-2 mb-md-0 text-uppercase fw-medium">Data Berdasarkan Kelompok Usia</h6>
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-striped project-orders-table">
                        <thead>
                            <tr>
                                <th class="ms-5">No</th>
                                <th>Kelompok Usia</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                            <tbody>
                                @php
                                    $totalKelompokUmurs = 0;
                                @endphp
                                @foreach($kelompokUmurCount as $index => $kelompokUmur)
                                    <tr>
                                        <td>{{ $index + 1 }}</td> 
                                        <td>{{ $kelompokUmur->nama_kelompok }}</td>
                                        <td>{{ $kelompokUmur->total_kelompok_umur }}</td>
                                    </tr>
                                @php
                                    $totalKelompokUmurs += $kelompokUmur->total_kelompok_umur;
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <th>{{ $totalKelompokUmurs }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
    <div class="col-md-12">
        <h5 class="mb-2 text-titlecase mb-4">Data Berdasarkan Pendidikan/ Profesi</h5>
        <div class="card">
        <div class="table-responsive pt-3">
            <table class="table table-striped project-orders-table">
                <thead>
                    <tr>
                        <th>Nama Bagian</th>
                        <th>Nama Profesi</th>
                        <th>Total Karyawan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bagians as $bagian)
                        <tr class="table-info"> <!-- Warna latar belakang untuk bagian -->
                            <td>{{ $bagian->nama_bagian }}</td>
                            <td colspan="2"></td> <!-- Baris Bagian -->
                        </tr>

                        @foreach($bagian->profesis as $profesi)
                            <tr>
                                <td></td>
                                <td>{{ $profesi->nama_profesi }}</td>
                                <td>{{ $profesi->total_employees }}</td>
                            </tr>
                        @endforeach

                        <tr class="table-warning"> <!-- Warna latar belakang untuk total bagian -->
                            <td colspan="2"><strong>Total Karyawan di Bagian {{ $bagian->nama_bagian }}:</strong></td>
                            <td><strong>{{ $bagian->total_employees }}</strong></td>
                        </tr>
                    @endforeach

                    <tr class="table-success"> <!-- Warna latar belakang untuk total keseluruhan -->
                        <td colspan="2"><strong>Total Karyawan Keseluruhan:</strong></td>
                        <td><strong>{{ $totalKaryawanKeseluruhan }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>
    </div>

</div>

@endsection