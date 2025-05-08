@extends('includeView.layout')
@section('content')

<p class="card-description"><h3>Riwayat Jabatan Pegawai</h3></p>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive" data-simplebar>
            <table id="dataTable" class="table table-borderless align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Tahun Mulai</th>
                        <th scope="col">Tahun Selesai</th>
                        <th scope="col">Dokumen</th>
                        <th scope="col">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="riwayatPendidikanTable">
                    @foreach($jabatan as $key => $riwayatJabatan)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $riwayatJabatan->tahun_mulai }}</td>
                            <td>{{ $riwayatJabatan->tahun_selesai }}</td>
                            <td>
                                @if($riwayatJabatan->dokumen != null)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDokumenJabatanModal{{ $riwayatJabatan->id }}">
                                        Tampilalkan Dokumen
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $riwayatJabatan->keterangan }}</td>
                        </tr>
                        
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Edit dan menampilkan gambar dengan cara terpisah -->
@foreach($jabatan as $key => $riwayatJabatan)

<!-- Modal untuk menampilkan gambar dokumen -->
<div class="modal fade" id="viewDokumenJabatanModal{{ $riwayatJabatan->id }}" tabindex="-1" aria-labelledby="viewDokumenModalJabatanLabel{{ $riwayatJabatan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDokumenModalJabatanLabel{{ $riwayatJabatan->id }}">Dokumen riwayat Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                @if($riwayatJabatan->dokumen)
                    @php
                        $extension = pathinfo($riwayatJabatan->dokumen, PATHINFO_EXTENSION);
                    @endphp
                    
                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ url($riwayatJabatan->dokumen) }}" alt="Dokumen" class="img-fluid">
                    @elseif($extension == 'pdf')
                        <iframe src="{{ url($riwayatJabatan->dokumen) }}" width="100%" height="500px"></iframe>
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