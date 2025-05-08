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
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenPendidikanModal{{ $riwayatPendidikan->id }}">
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
@foreach($pendidikan as $key => $riwayatPendidikan)

    <!-- Modal untuk menampilkan gambar dokumen -->
    <div class="modal fade" id="viewDokumenPendidikanModal{{ $riwayatPendidikan->id }}" tabindex="-1" aria-labelledby="viewDokumenPendidikanModalLabel{{ $riwayatPendidikan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDokumenPendidikanModalLabel{{ $riwayatPendidikan->id }}">Dokumen riwayat Sekolah/Universitas {{ $riwayatPendidikan->nama_sekolah }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                @if($riwayatPendidikan->dokumen)
                    @php
                        $extension = pathinfo($riwayatPendidikan->dokumen, PATHINFO_EXTENSION);
                    @endphp
                    
                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ url($riwayatPendidikan->dokumen) }}" alt="Dokumen" class="img-fluid">
                    @elseif($extension == 'pdf')
                        <iframe src="{{ url($riwayatPendidikan->dokumen) }}" width="100%" height="500px"></iframe>
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