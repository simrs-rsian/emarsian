@extends('includeView.layout')
@section('content')
<p class="card-description"><h3>Riwayat Diklat dan Pelatihan</h3></p>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive" data-simplebar>
            <table id="dataTable" class="table table-borderless align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Pelatihan</th>
                        <th scope="col">Tanggal Mulai</th>
                        <th scope="col">Tanggal Selesai</th>
                        <th scope="col">Penyelenggara</th>
                        <th scope="col">Lokasi</th>
                        <th scope="col">Poin</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Dokumen</th>
                    </tr>
                </thead>
                <tbody id="riwayatPelatihanTable">
                    @foreach($pelatihan as $key => $riwayatPelatihan)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $riwayatPelatihan->nama_pelatihan }}</td>
                            <td>{{ $riwayatPelatihan->tanggal_mulai }}</td>
                            <td>{{ $riwayatPelatihan->tanggal_selesai }}</td>
                            <td>{{ $riwayatPelatihan->penyelenggara }}</td>
                            <td>{{ $riwayatPelatihan->lokasi }}</td>
                            <td>{{ $riwayatPelatihan->poin }}</td>
                            <td>{{ $riwayatPelatihan->nama_jenis }}</td>
                            <td>
                                @if($riwayatPelatihan->dokumen)
                                    <span class="badge bg-success">Dokumen Tersedia Hubungi Admin</span> 
                                @else
                                    <span class="badge bg-danger">Tidak ada dokumen</span>
                                @endif
                            </td>
                        </tr>
                        
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
