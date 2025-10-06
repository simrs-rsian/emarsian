@extends('includeView.layout')
@section('content')

<p class="card-description"><h3>Riwayat Pendidikan</h3></p>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive" data-simplebar>
            <table id="dataTable" class="table table-borderless align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Tahun Masuk</th>
                        <th scope="col">Tahun Lulus</th>
                        <th scope="col">Sekolah/Universitas</th>
                        <th scope="col">Lokasi</th>
                        <th scope="col">Dokumen</th>
                    </tr>
                </thead>
                <tbody id="riwayatPendidikanTable">
                    @foreach($pendidikan as $key => $riwayatPendidikan)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $riwayatPendidikan->tahun_masuk }}</td>
                            <td>{{ $riwayatPendidikan->tahun_lulus }}</td>
                            <td>{{ $riwayatPendidikan->nama_sekolah }}</td>
                            <td>{{ $riwayatPendidikan->lokasi }}</td>
                            <td>
                                @if($riwayatPendidikan->dokumen)
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