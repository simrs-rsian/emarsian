@extends('includeView.layout')
@section('content')

<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <h5>Panduan Impor Data Karyawan Menggunakan Excel</h5>

            <p>Ikuti langkah-langkah berikut untuk mengunggah file Excel berisi data karyawan dan mengimpornya ke dalam sistem:</p>

            <ol>
                <li>
                    <strong>Siapkan File Excel</strong>
                    <br>Download file Excel dengan format <code>.xlsx</code> yang berisi data karyawan berikut ini dan Pastikan file berisi sesuai kolom yang diperlukan. <br>
                    <a href="{{ route('employee.download.import.template') }}">Download Import Template</a>
                </li>

                <li>
                    <strong>Kunjungi Halaman Unggah Data</strong>
                    <br>Buka aplikasi excel yang sudah ter-unduh dengan nama <strong>format_data_karyawan.xlsx</strong>.
                </li>

                <li>
                    <strong>Setting Data Excel</strong>
                    <br>Sesuaikan dengan contoh yang ada, <strong> untuk format Tanggal gunakan urutan tahun-bulan-tanggal (YYYY-MM-DD) contoh: 2024-02-24</strong> dan <strong> untuk golongan, profesi, jabatan, status keluarga, status pegawai dan pendidikan gunakan ID yang sudah disediakan dalam menu master data</strong>.
                </li>

                <li>
                    <strong>Unggah File Excel</strong>
                    <br>Klik tombol <strong>Pilih File</strong> dan pilih file Excel yang sudah Anda siapkan. Pastikan format file sesuai dengan ketentuan <code>.xlsx</code>.
                </li>

                <li>
                    <strong>Impor Data</strong>
                    <br>Setelah memilih file, klik tombol <strong>Unggah dan Import</strong>. Sistem akan memproses file tersebut dan memasukkan data ke dalam database.
                </li>

                <li>
                    <strong>Tunggu Konfirmasi</strong>
                    <br>Setelah proses selesai, sistem akan memberikan notifikasi apakah proses impor berhasil atau tidak. Jika ada kesalahan, periksa file Excel dan ulangi prosesnya.
                </li>

                <li>
                    <strong>Periksa Data yang Diimpor</strong>
                    <br>Setelah impor berhasil, buka halaman <strong>Daftar Karyawan</strong> untuk memastikan data yang diunggah sudah masuk dengan benar.
                </li>
            </ol>

            <h1> Upload File</h1>

            <form action="{{ route('employee.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file">Pilih file Excel:</label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                    @error('file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Import Data</button>
                <a href="{{ route('employee.index') }}" class="btn btn-danger">Batal</a>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk preview gambar sebelum upload
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('image-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
