@extends('includeView.layout')
@section('content')

<p class="card-description"><h3>Riwayat Kontrak</h3></p>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive" data-simplebar>
            <table id="dataTable" class="table table-borderless align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Tanggal Mulai</th>
                        <th scope="col">Tanggal Selesai</th>
                        <th scope="col">Dokumen</th>
                    </tr>
                </thead>
                <tbody id="riwayatPendidikanTable">
                    @foreach($kontrak as $key => $riwayatKontrak)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $riwayatKontrak->tanggal_mulai }}</td>
                            <td>{{ $riwayatKontrak->tanggal_selesai }}</td>
                            <td>
                                @if($riwayatKontrak->dokumen)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenKontrakModal{{ $riwayatKontrak->id }}">
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
@foreach($kontrak as $key => $riwayatKontrak)

    <!-- Modal untuk menampilkan gambar dokumen -->
    <div class="modal fade" id="viewDokumenKontrakModal{{ $riwayatKontrak->id }}" tabindex="-1" aria-labelledby="viewDokumenKontrakModalLabel{{ $riwayatKontrak->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDokumenKontrakModalLabel{{ $riwayatKontrak->id }}">Dokumen riwayat Kontrak tanggal Akhir {{ $riwayatKontrak->tanggal_selesai }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    @if($riwayatKontrak->dokumen)
                        @php
                            $extension = pathinfo($riwayatKontrak->dokumen, PATHINFO_EXTENSION);
                        @endphp
                        
                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ url($riwayatKontrak->dokumen) }}" alt="Dokumen" class="img-fluid">
                        @elseif($extension == 'pdf')
                            <iframe src="{{ url($riwayatKontrak->dokumen) }}" width="100%" height="500px"></iframe>
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