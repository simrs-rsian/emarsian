@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Inventaris</h4>
                    <p class="card-description">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </p>
                    <div class="table-responsive pt-3">
                        <form action="{{ route('inventaris.update', str_replace('/', '|', $inventaris->no_inventaris)) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">No Inventaris</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="no_inventaris" value="{{ $inventaris->no_inventaris }}" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Nama Barang</label>
                                        <div class="col-sm-9">
                                            <select name="kode_barang" class="form-control select2-barang" id="">
                                                @foreach ($barangs as $item)
                                                    <option value="{{ $item->kode_barang }}" {{ $inventaris->kode_barang == $item->kode_barang ? 'selected' : '' }}>({{ $item->kode_barang }}) {{ $item->nama_barang }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Asal Barang</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="asal_barang" required>
                                                <option value="Beli" {{ $inventaris->asal_barang == 'Beli' ? 'selected' : '' }}>Beli</option>
                                                <option value="Bantuan" {{ $inventaris->asal_barang == 'Bantuan' ? 'selected' : '' }}>Bantuan</option>
                                                <option value="Hibah" {{ $inventaris->asal_barang == 'Hibah' ? 'selected' : '' }}>Hibah</option>
                                                <option value="-" {{ $inventaris->asal_barang == '-' ? 'selected' : '' }}>-</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Tanggal Pengadaan</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" name="tgl_pengadaan" value="{{ $inventaris->tgl_pengadaan }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Harga</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" name="harga" value="{{ $inventaris->harga }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Status Barang</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="status_barang" required>
                                                <option value="Ada" {{ $inventaris->status_barang == 'Ada' ? 'selected' : '' }}>Ada</option>
                                                <option value="Rusak" {{ $inventaris->status_barang == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                                <option value="Hilang" {{ $inventaris->status_barang == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                                                <option value="Perbaikan" {{ $inventaris->status_barang == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                                <option value="Dipinjam" {{ $inventaris->status_barang == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                                <option value="-" {{ $inventaris->status_barang == '-' ? 'selected' : '' }}>-</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Nama Ruang Ruang</label>
                                        <div class="col-sm-9">
                                            <select name="id_ruang" class="form-control select2-ruang" id="" required>
                                                @foreach ($ruangs as $item)
                                                    <option value="{{ $item->id_ruang }}" {{ $inventaris->id_ruang == $item->id_ruang ? 'selected' : '' }}>({{ $item->id_ruang }}) {{ $item->nama_ruang }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">No Rak</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="no_rak" value="{{ $inventaris->no_rak }}" placeholder="Isi dengan angka, jika tidak ada isi dengan tanda -" pattern="\d+|-" title="Hanya angka atau tanda -" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">No Box</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="no_box" value="{{ $inventaris->no_box }}" placeholder="Isi dengan angka, jika tidak ada isi dengan tanda -" pattern="\d+|-" title="Hanya angka atau tanda -" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan script inisialisasi DataTable setelah halaman siap -->
@endsection
