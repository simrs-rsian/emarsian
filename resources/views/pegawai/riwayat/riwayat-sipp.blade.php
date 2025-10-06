@extends('includeView.layout')
@section('content')
<p class="card-description"><h3>Riwayat SIP</h3></p>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive" data-simplebar>
            <table class="table table-borderless align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">No SIP</th>
                        <th scope="col">No STR</th>
                        <th scope="col">Tanggal Akhir Berlaku</th>
                        <th scope="col">Dokumen</th>
                    </tr>
                </thead>
                <tbody id="riwayatPendidikanTable">
                    @foreach($sipp as $key => $riwayatSipp)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $riwayatSipp->no_sipp }}</td>
                            <td>{{ $riwayatSipp->no_str }}</td>
                            <td>{{ $riwayatSipp->tanggal_berlaku }}</td>
                            <td>
                                @if($riwayatSipp->dokumen)
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