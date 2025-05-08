@extends('includeView.layout')
@section('content')

<p class="card-description"><h3>Riwayat Keluarga</h3></p>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive" data-simplebar>
            <table id="dataTable" class="table table-borderless align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">Status Keluarga</th>
                        <th scope="col">Pekerjaan</th>
                        <th scope="col">Pendidikan Terkahir</th>
                        <th scope="col">Dokumen</th>
                    </tr>
                </thead>
                <tbody id="riwayatPendidikanTable">
                    @foreach($keluarga as $key => $riwayatKeluarga)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $riwayatKeluarga->nama_keluarga }}</td>
                            <td>{{ $riwayatKeluarga->status_keluarga }}</td>
                            <td>{{ $riwayatKeluarga->pekerjaan_keluarga }}</td>
                            <td>{{ $riwayatKeluarga->pendidikan_keluarga }}</td>
                            <td>
                            <td>
                                @if($riwayatKeluarga->dokumen)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenKeluargaModal{{ $riwayatKeluarga->id }}">
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

<!-- Edit Riwayat Keluarga Modal -->
@foreach($keluarga as $riwayatKeluarga)
<!-- Modal untuk menampilkan gambar dokumen -->
<div class="modal fade" id="viewDokumenKeluargaModal{{ $riwayatKeluarga->id }}" tabindex="-1" aria-labelledby="viewDokumenKeluargaModalLabel{{ $riwayatKeluarga->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDokumenKeluargaModalLabel{{ $riwayatKeluarga->id }}">Dokumen Nama {{ $riwayatKeluarga->nama_keluarga }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                @if($riwayatKeluarga->dokumen)
                    @php
                        $extension = pathinfo($riwayatKeluarga->dokumen, PATHINFO_EXTENSION);
                    @endphp
                    
                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ url($riwayatKeluarga->dokumen) }}" alt="Dokumen" class="img-fluid">
                    @elseif($extension == 'pdf')
                        <iframe src="{{ url($riwayatKeluarga->dokumen) }}" width="100%" height="500px"></iframe>
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