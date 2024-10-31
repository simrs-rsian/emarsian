@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Laporan Data Pelatihan</h4>
                    <p class="card-description">
                        <form method="GET" action="{{ route('pelatihan.report') }}">
                            <div class="form-group">
                                <label for="tahun">Filter Tahun</label>
                                <input type="number" name="tahun" id="tahun" class="form-control" value="{{ request('tahun', date('Y')) }}" placeholder="Masukkan tahun">
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </p>
                    <div class="table-responsive pt-3">
                        <h2>Tahun: {{ $tahun }}</h2>
                        <table id="dataTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Pegawai</th>
                                    @foreach ($dataPelatihan as $pelatihan)
                                        <th>{{ $pelatihan->nama_pelatihan }}<br>(Poin: {{ $pelatihan->poin }})</th>
                                    @endforeach
                                    <th>Total Poin</th>
                                    <th>Pencapaian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalPoin = 0;
                                    $totalPencapaian = 0;
                                    $jumlahPegawai = count($pegawai);
                                @endphp
                                @foreach ($pegawai as $id => $namaPegawai)
                                    <tr>
                                        <td>{{ $namaPegawai }}</td>
                                        @foreach ($dataPelatihan as $pelatihan)
                                            <td>{{ $pegawaiData[$id]['pelatihan'][$pelatihan->nama_pelatihan] ?? '-' }}</td>
                                        @endforeach
                                        <td>{{ $pegawaiData[$id]['total'] ?? 0 }}</td>
                                        <td>{{ $pegawaiData[$id]['pencapaian'] ?? 0 }}</td>
                                    </tr>

                                    @php
                                        $totalPoin += $pegawaiData[$id]['total'] ?? 0;
                                        $totalPencapaian += $pegawaiData[$id]['pencapaian'] ?? 0;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                @if ($jumlahPegawai > 0)
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        @foreach ($dataPelatihan as $pelatihan)
                                            <td>-</td>
                                        @endforeach
                                        <td><strong>{{ $totalPoin }}</strong></td>
                                        <td><strong>{{ $totalPencapaian }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Persentase</strong></td>
                                        @foreach ($dataPelatihan as $pelatihan)
                                            <td>-</td>
                                        @endforeach
                                        <td colspan="2">
                                            @if ($jumlahPegawai > 0)
                                                <strong>{{ number_format(($totalPencapaian / $jumlahPegawai) * 100, 2) }}%</strong>
                                            @else
                                                <strong>0%</strong>
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="{{ count($dataPelatihan) + 3 }}" class="text-center">Tidak ada data pelatihan untuk tahun ini.</td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
