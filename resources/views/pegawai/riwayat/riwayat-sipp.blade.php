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
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenSippModal{{ $riwayatSipp->id }}">
                                        Tampilalkan Dokumen
                                    </button>
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
<!-- Edit dan menampilkan gambar dengan cara terpisah -->
@foreach($sipp as $key => $riwayatSipp)
    <!-- Modal untuk menampilkan gambar dokumen -->
    <div class="modal fade" id="viewDokumenSippModal{{ $riwayatSipp->id }}" tabindex="-1" aria-labelledby="viewDokumenSippModalLabel{{ $riwayatSipp->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDokumenSippModalLabel{{ $riwayatSipp->id }}">Dokumen riwayat SIP dengan Nomor.  {{ $riwayatSipp->no_sipp }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    @if($riwayatSipp->dokumen)
                        @php
                            $extension = pathinfo($riwayatSipp->dokumen, PATHINFO_EXTENSION);
                        @endphp
                        
                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ url($riwayatSipp->dokumen) }}" alt="Dokumen" class="img-fluid">
                        @elseif($extension == 'pdf')
                            <iframe src="{{ url($riwayatSipp->dokumen) }}" width="100%" height="500px"></iframe>
                        @else
                            <p>Tipe dokumen tidak didukung.</p>
                        @endif
                    @else
                        <p>Dokumen tidak tersedia.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection