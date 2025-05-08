@extends('includeView.layout')
@section('content')

<p class="card-description"><h3>Riwayat Lain-Lain</h3></p>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive" data-simplebar>
            <table id="dataTable" class="table table-borderless align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Riwayat</th>
                        <th scope="col">Tanggal Riwayat</th>
                        <th scope="col">Dokumen</th>
                    </tr>
                </thead>
                <tbody id="riwayatPendidikanTable">
                    @foreach($lain as $key => $riwayatLain)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $riwayatLain->nama_riwayat }}</td>
                            <td>{{ $riwayatLain->tanggal_riwayat }}</td>
                            <td>
                                @if($riwayatLain->dokumen)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenLainModal{{ $riwayatLain->id }}">
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
@foreach($lain as $key => $riwayatLain)
    <!-- Modal untuk menampilkan gambar dokumen -->
    <div class="modal fade" id="viewDokumenLainModal{{ $riwayatLain->id }}" tabindex="-1" aria-labelledby="viewDokumenLainModalLabel{{ $riwayatLain->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDokumenLainModalLabel{{ $riwayatLain->id }}">Dokumen {{ $riwayatLain->nama_riwayat }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    @if($riwayatLain->dokumen)
                        @php
                            $extension = pathinfo($riwayatLain->dokumen, PATHINFO_EXTENSION);
                        @endphp
                        
                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ url($riwayatLain->dokumen) }}" alt="Dokumen" class="img-fluid">
                        @elseif($extension == 'pdf')
                            <iframe src="{{ url($riwayatLain->dokumen) }}" width="100%" height="500px"></iframe>
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