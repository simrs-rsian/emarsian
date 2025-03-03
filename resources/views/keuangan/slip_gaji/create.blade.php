@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Buat Slip Gaji Karyawan</h4>
                    <p class="card-description">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </p>
                    <div class="table-responsive pt-3">
                        <table class="table table-stripped">
                            <tr>
                                <td style="width: 200px;">NIP Karyawan</td>
                                <td>:</td>
                                <td>{{ $employees->nip_karyawan }}</td>
                                <td style="width: 200px;">Jabatan</td>
                                <td>:</td>
                                <td>{{ $employees->unit->nama_unit }}</td>
                            </tr>
                            <tr>
                                <td>Nama Lengkap</td>
                                <td>:</td>
                                <td>{{ $employees->nama_lengkap }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <br>
                        <form action="{{ route('slip_gaji.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bulan">Bulan</label>
                                            @php
                                                $months = [
                                                    1 => 'Januari',
                                                    2 => 'Februari',
                                                    3 => 'Maret',
                                                    4 => 'April',
                                                    5 => 'Mei',
                                                    6 => 'Juni',
                                                    7 => 'Juli',
                                                    8 => 'Agustus',
                                                    9 => 'September',
                                                    10 => 'Oktober',
                                                    11 => 'November',
                                                    12 => 'Desember'
                                                ];
                                            @endphp
                                            <input type="text" class="form-control" id="bulan" value="{{ $months[$bulan] }}" required readonly>
                                            <input type="hidden" name="bulan" value="{{ $bulan }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tahun">Tahun</label>
                                        <input type="number" name="tahun" class="form-control" id="tahun" value="{{ $tahun }}" required readonly>
                                    </div>                                
                                </div>
                            </div>

                            <input type="hidden" name="employee_id" value="{{ $employees->id }}">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Gaji</h5>
                                    @foreach($settinggajis as $gaji)
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">{{ $gaji->gaji_nama }}</label>
                                            <div class="col-sm-8">
                                                <input type="hidden" name="nama_gaji[{{ $gaji->id }}]" value="{{ $gaji->gaji_nama }}">
                                                <input type="hidden" name="gaji[{{ $gaji->id }}]" id="gaji_{{ $gaji->id }}" value="{{ $gaji->nominal ?? '0' }}">
                                                <input type="text" name="gaji_display[{{ $gaji->id }}]" class="form-control" data-hidden-target="gaji_{{ $gaji->id }}" value="{{ number_format($gaji->nominal ?? 0, 0, ',', '.') }}" required>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                                <div class="col-md-6">
                                    <h5>Potongan</h5>
                                    @foreach($settingpotongans as $potongan)
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">{{ $potongan->gaji_nama }}</label>
                                            <div class="col-sm-8">
                                                <input type="hidden" name="nama_potongan[{{ $potongan->id }}]" value="{{ $potongan->gaji_nama }}">
                                                <input type="hidden" name="potongan[{{ $potongan->id }}]" id="potongan_{{ $potongan->id }}" value="{{ $potongan->nominal ?? '0' }}">
                                                <input type="text" name="potongan_display[{{ $potongan->id }}]" class="form-control" data-hidden-target="potongan_{{ $potongan->id }}" value="{{ number_format($potongan->nominal ?? 0, 0, ',', '.') }}" required>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_gaji_display">Total Gaji</label>
                                        <input type="hidden" name="total_gaji" id="total_gaji">
                                        <input type="text" class="form-control" id="total_gaji_display" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_potongan_display">Total Potongan</label>
                                        <input type="hidden" name="total_potongan" id="total_potongan">
                                        <input type="text" class="form-control" id="total_potongan_display" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="take_home_pay_display"><strong>Take Home Pay</strong></label>
                                        <input type="hidden" name="take_home_pay" id="take_home_pay">
                                        <input type="text" class="form-control" id="take_home_pay_display" readonly style="font-weight: bold; color: green;">
                                    </div>
                                </div>
                            </div>
                            <br>
                            @if ($validasislip > 0)
                                <a href="{{ route('slip_gaji.CetakSlipPenggajian', ['id' => $employees->id , 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-warning" target="_blank">Cetak Slip Gaji</a>
                            @else
                                <button type="submit" class="btn btn-primary">Simpan Slip Gaji</button> 
                            @endif
                            <a href="{{ route('slip_gaji.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function formatRupiah(number) {
        return number.toLocaleString("id-ID"); // Format angka dengan titik sebagai pemisah ribuan
    }

    function calculateTotal(selector, targetId, hiddenTargetId) {
        let total = 0;
        document.querySelectorAll(selector).forEach(input => {
            let hiddenInput = document.getElementById(input.dataset.hiddenTarget);
            let value = parseFloat(hiddenInput.value) || 0;
            total += value;
        });
        document.getElementById(targetId).value = formatRupiah(total); // Tampilkan dalam format rupiah
        document.getElementById(hiddenTargetId).value = total; // Simpan nilai asli di input hidden
    }

    function calculateTakeHomePay() {
        let totalGaji = parseFloat(document.getElementById("total_gaji").value) || 0;
        let totalPotongan = parseFloat(document.getElementById("total_potongan").value) || 0;
        let takeHomePay = totalGaji - totalPotongan;

        document.getElementById("take_home_pay_display").value = formatRupiah(takeHomePay);
        document.getElementById("take_home_pay").value = takeHomePay;
    }

    function updateTotals() {
        calculateTotal("input[name^='gaji_display']", "total_gaji_display", "total_gaji");
        calculateTotal("input[name^='potongan_display']", "total_potongan_display", "total_potongan");
        calculateTakeHomePay();
    }

    function formatInputRupiah(event) {
        let input = event.target;
        let hiddenInput = document.getElementById(input.dataset.hiddenTarget);

        let rawValue = input.value.replace(/\./g, ""); // Hapus titik sebelum parsing angka
        let numericValue = parseFloat(rawValue) || 0; // Konversi ke angka
        hiddenInput.value = numericValue; // Simpan nilai asli tanpa format

        input.value = formatRupiah(numericValue); // Format tampilan dengan titik
        updateTotals(); // Perbarui total dan THP
    }

    // Tambahkan event listener ke semua input gaji dan potongan
    document.querySelectorAll("input[name^='gaji_display'], input[name^='potongan_display']").forEach(input => {
        input.addEventListener("input", formatInputRupiah);
    });

    // Hitung total saat halaman dimuat
    updateTotals();
});
</script>
<script>
    document.getElementById("form-slip-gaji").addEventListener("submit", function(event) {
    event.preventDefault(); // Mencegah reload halaman
    
    let formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            window.open(data.redirect_url, '_blank'); // Buka tab baru
            window.location.reload(); // Reload halaman utama
        } else {
            alert("Terjadi kesalahan: " + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

</script>
@endsection
