@extends('includeView.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Slip Penggajian</h4>
                    <p class="card-description">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </p>
                    <div class="table-responsive pt-3">
                        <form action="{{ route('slip_gaji.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bulan">Pilih Bulan</label>
                                        <select name="bulan" class="form-control" id="bulan">
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
                                                $currentMonth = request()->input('bulan', date('n'));
                                            @endphp
                                            @foreach ($months as $key => $month)
                                                <option value="{{ $key }}" {{ $currentMonth == $key ? 'selected' : '' }}>{{ $month }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tahun">Pilih Tahun</label>
                                        <select name="tahun" class="form-control" id="tahun">
                                            @php
                                                $currentYear = request()->input('tahun', date('Y'));
                                                $startYear = $currentYear - 3;
                                                $endYear = $currentYear + 5;
                                            @endphp
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tahun"></label>
                                        <button type="submit" class="btn btn-warning" style="width: 100%">Filter</button>
                                    </div>
                                </div>
                            </div><br>
                        </form>
                        <form action="{{ route('slip_gaji.storeAllSlip') }}" method="POST">
                            @csrf
                            <input type="hidden" name="bulan" value="{{ request()->input('bulan', date('n')) }}">
                            <input type="hidden" name="tahun" value="{{ request()->input('tahun', date('Y')) }}">

                            <table id="employeeSlipTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>No</th>
                                        <th>NIP Karyawan</th>
                                        <th>Nama Lengkap</th>
                                        <th>Rincian Gaji</th>
                                        <th>Rincian Potongan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $key => $employee)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_employees[]" value="{{ $employee->id }}" class="select-employee">
                                            </td>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $employee->nip_karyawan }}</td>
                                            <td>{{ $employee->nama_lengkap }}</td>
                                            <td>
                                                @if($settinggajis->where('employee_id', $employee->id)->isEmpty())
                                                    <p>Data belum tersetting</p>
                                                @else
                                                    <button type="button" class="btn btn-info btn-sm rincian-gaji-btn" data-id="{{ $employee->id }}">Rincian Gaji</button>
                                                    <div class="gaji-dropdown" id="gaji-dropdown-{{ $employee->id }}" style="display:none;">
                                                        @foreach($settinggajis as $gaji)
                                                            @if($employee->id == $gaji->employee_id)
                                                                <div class="form-group">
                                                                    <label>{{ $gaji->gaji_nama }}</label>
                                                                    <input type="hidden" name="nama_gaji[{{ $gaji->id }}]" value="{{ $gaji->gaji_nama }}">
                                                                    <input type="number" name="gaji[{{ $employee->id }}][{{ $gaji->id }}]" class="form-control" value="{{ $gaji->nominal }}" required>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($settingpotongans->where('employee_id', $employee->id)->isEmpty())
                                                    <p>Data belum tersetting</p>
                                                @else
                                                    <button type="button" class="btn btn-warning btn-sm rincian-potongan-btn" data-id="{{ $employee->id }}">Rincian Potongan</button>
                                                    <div class="potongan-dropdown" id="potongan-dropdown-{{ $employee->id }}" style="display:none;">
                                                        @foreach($settingpotongans as $potongan)
                                                            @if($employee->id == $potongan->employee_id)
                                                                <div class="form-group">
                                                                    <label>{{ $potongan->gaji_nama }}</label>
                                                                    <input type="hidden" name="nama_gaji[{{ $potongan->id }}]" value="{{ $potongan->gaji_nama }}">
                                                                    <input type="number" name="potongan[{{ $employee->id }}][{{ $potongan->id }}]" class="form-control" value="{{ $potongan->nominal }}" required>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($settinggajis->where('employee_id', $employee->id)->isEmpty())
                                                    <a href="{{ route('setting_gaji.show', $employee->id) }}" class="btn btn-danger btn-sm">Setting Gaji</a>
                                                @else
                                                    @if($SlipPenggajians->where('employee_id', $employee->id)->isEmpty())
                                                        <a href="{{ route('slip_gaji.create', ['employee_id' => $employee->id, 'bulan' => request()->input('bulan', date('n')), 'tahun' => request()->input('tahun', date('Y'))]) }}" class="btn btn-primary btn-sm">Buat Slip Gaji</a>
                                                    @else
                                                        <a href="{{ route('slip_gaji.CetakSlipPenggajian', ['id' => $employee->id, 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-warning btn-sm" target="_blank">Cetak Slip Gaji</a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <button type="submit" class="btn btn-primary">Simpan Slip Gaji</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    // Pastikan hanya menginisialisasi tabel yang benar
    var table = $('#employeeSlipTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "paging": true,
        "searching": true,
        "ordering": true,
    });
});
</script>


<!-- JavaScript untuk Toggle Checkbox -->
<script>
    document.getElementById('selectAll').addEventListener('click', function () {
        let checkboxes = document.querySelectorAll('.select-employee');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Script untuk menampilkan rincian gaji/potongan
    document.querySelectorAll('.rincian-gaji-btn').forEach(button => {
        button.addEventListener('click', function () {
            let id = this.getAttribute('data-id');
            let dropdown = document.getElementById('gaji-dropdown-' + id);
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        });
    });

    document.querySelectorAll('.rincian-potongan-btn').forEach(button => {
        button.addEventListener('click', function () {
            let id = this.getAttribute('data-id');
            let dropdown = document.getElementById('potongan-dropdown-' + id);
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        });
    });
</script>

@endsection
