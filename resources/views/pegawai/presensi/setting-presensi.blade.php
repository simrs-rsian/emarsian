@extends('includeView.layout')
@section('title', 'Setting Presensi')
@push('css')
@endpush
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Setting Data Presensi</h4>
                    <p class="card-description">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    @if($pegawai_datas->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            Akses Terbatas Untuk Kepala Ruangan.
                        </div>
                    @else
                      <table id="dataTable" class="table table-striped">
                          <thead>
                              <tr>
                                  <th>No</th>
                                  <th>Nama Pegawai</th>
                                  <th>Bagian/Departemen</th>
                                  <th>Jabatan</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse($pegawai_datas as $key => $pegawai)
                                  <tr>
                                      <td>{{ $key+1 }}</td>
                                      <td>{{ $pegawai->nama_pegawai }}</td>
                                      <td>{{ $pegawai->nama_departemen }}</td>
                                      <td>{{ $pegawai->jabatan }}</td>
                                      <td>
                                          <a href="{{ route('pegawai.setPresensi.show', $pegawai->id) }}" class="btn btn-sm btn-primary">Setting Jadwal</a>
                                          <a href="{{ route('pegawai.setRiwayatPresensi.show', $pegawai->id) }}" class="btn btn-sm btn-secondary">Riwayat Presensi</a>
                                      </td>
                                  </tr>
                              @empty
                                  <tr>
                                      <td colspan="5" class="text-center">Tidak ada data</td>
                                  </tr>
                              @endforelse
                          </tbody>
                      </table>
                    @endif
                  </div>
              </div>

            </div>
        </div>
    </div>
</div>

<!-- Tambahkan script inisialisasi DataTable setelah halaman siap -->


@endsection
