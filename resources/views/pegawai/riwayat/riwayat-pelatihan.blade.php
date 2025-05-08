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
                            <td>{{ $riwayatPelatihan->id }}</td>
                            <td>{{ $riwayatPelatihan->nama_pelatihan }}</td>
                            <td>{{ $riwayatPelatihan->tanggal_mulai }}</td>
                            <td>{{ $riwayatPelatihan->tanggal_selesai }}</td>
                            <td>{{ $riwayatPelatihan->penyelenggara }}</td>
                            <td>{{ $riwayatPelatihan->lokasi }}</td>
                            <td>{{ $riwayatPelatihan->poin }}</td>
                            <td>{{ $riwayatPelatihan->nama_jenis }}</td>
                            <td>
                                @if($riwayatPelatihan->dokumen)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenPelatihanModal{{ $riwayatPelatihan->id }}">
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
@foreach($pelatihan as $key => $riwayatPelatihan)
    <!-- Modal untuk menampilkan gambar dokumen -->
<div class="modal fade" id="viewDokumenPelatihanModal{{ $riwayatPelatihan->id }}" tabindex="-1" aria-labelledby="viewDokumenModalPelatihanLabel{{ $riwayatPelatihan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDokumenModalPelatihanLabel{{ $riwayatPelatihan->id }}">Dokumen riwayat Pelatihan {{ $riwayatPelatihan->penyelenggara }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                @if($riwayatPelatihan->dokumen)
                    @php
                        $extension = pathinfo($riwayatPelatihan->dokumen, PATHINFO_EXTENSION);
                    @endphp
                    
                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ url($riwayatPelatihan->dokumen) }}" alt="Dokumen" class="img-fluid">
                    @elseif($extension == 'pdf')
                        <iframe src="{{ url($riwayatPelatihan->dokumen) }}" width="100%" height="500px"></iframe>
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
