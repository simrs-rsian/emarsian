@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <h3>Assalamu'alikum Warohmatullohiwabarakatuh... <br>{{$employee->nama_lengkap}} Sebagai Karyawan RSI Nganjuk, Selamat Datang</h3>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center flex-wrap mb-4">
                <div>
                    <!-- {{$employee->photo}} -->
                    <img src="{{ $employee->photo ? url($employee->photo) : url('src/assets/images/user.png') }}" 
                             alt="Foto Karyawan" 
                             class="img-thumbnail mb-3 text-center" 
                             style="max-width: 200px; height: auto;">
                    <p class="mb-2 text-center">{{$employee->nama_lengkap}} <br> ({{$employee->nip_karyawan}})</p>
                    
                </div>
                <i class="typcn typcn-briefcase icon-xl text-secondary"></i>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-md-between flex-wrap mb-4">
                <div>
                    <p class="mb-2 text-md-center text-lg-left">Data Riwayat Pelatihan</p>
                    <h1 class="mb-0">{{ $totalRiwayatPelatihan }}</h1>
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
                    <p class="mb-2 text-md-center text-lg-left">Data Riwayat Absen Bulan ini</p>
                    <h1 class="mb-0"></h1>
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
                    <p class="mb-2 text-md-center text-lg-left">Data Riwayat Keluarga</p>
                    <h1 class="mb-0">{{ $totalRiwayatKeluarga }}</h1>
                </div>
                <i class="typcn typcn-clipboard icon-xl text-secondary"></i>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

@endsection