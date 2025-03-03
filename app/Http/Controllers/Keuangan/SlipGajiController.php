<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Keuangan\RincianSlipGaji;
use App\Models\Keuangan\RincianSlipPotongan;
use App\Models\Keuangan\SlipPenggajian;
use App\Models\Keuangan\SettingGaji;
use App\Models\Keuangan\SettingPotongan;
use App\Models\Employee\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class SlipGajiController extends Controller
{
    public function index(Request $request)
    {
        $bulan                  = $request->input('bulan', date('m'));
        $tahun                  = $request->input('tahun', date('Y'));
        $SlipPenggajians        = SlipPenggajian::where('bulan', $bulan)->where('tahun', $tahun)->get();
        $rincianslipgaji        = RincianSlipGaji::all();
        $rincianslippotongan    = RincianSlipPotongan::all();
        $settinggajis           = SettingGaji::leftjoin('default_gajis', 'setting_gajis.default_gaji_id', '=', 'default_gajis.id') ->where('setting_gajis.nominal', '!=', 0)->where('default_gajis.mode_id', 1)->get();
        $settingpotongans       = SettingPotongan::leftjoin('default_gajis', 'setting_potongans.default_gaji_id', '=', 'default_gajis.id') ->where('setting_potongans.nominal', '!=', 0)->where('default_gajis.mode_id', 2)->get();
        $employees = Employee::all();
        return view('keuangan.slip_gaji.index', compact('rincianslipgaji', 'rincianslippotongan', 'settinggajis', 'settingpotongans', 'employees', 'SlipPenggajians', 'bulan', 'tahun'));
    }

    public function create(Request $request)
    {
        // dd($request->all());
        $bulan          = $request->bulan;
        $tahun          = $request->tahun;
        $id_employee    = $request->employee_id;
        $settinggajis   = SettingGaji::
                            leftjoin('default_gajis', 'setting_gajis.default_gaji_id', '=', 'default_gajis.id')              ->where('setting_gajis.nominal', '!=', 0)
                            ->where('default_gajis.mode_id', 1)
                            ->where('setting_gajis.employee_id', $id_employee)
                            ->get();
        $settingpotongans = SettingPotongan::
                            leftjoin('default_gajis', 'setting_potongans.default_gaji_id', '=', 'default_gajis.id') 
                            ->where('setting_potongans.nominal', '!=', 0)
                            ->where('default_gajis.mode_id', 2)
                            ->where('setting_potongans.employee_id', $id_employee)
                            ->get();
        $employees = Employee::with('golongan', 'unit')->where('id', $id_employee)->first();
        $validasislip = SlipPenggajian::where('bulan', $bulan)->where('tahun', $tahun)->where('employee_id', $id_employee)->count();
        return view('keuangan.slip_gaji.create', compact('settinggajis', 'settingpotongans', 'employees', 'bulan', 'tahun', 'validasislip'));
    }

    public function store(Request $request)
    {
        // dd  ($request->all());
        // Validasi data
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:'.date('Y'),
            'employee_id' => 'required|exists:employees,id',
            'gaji' => 'required|array',
            'gaji.*' => 'required|numeric|min:0',
            'potongan' => 'nullable|array',
            'potongan.*' => 'nullable|numeric|min:0',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $employee_id = $request->employee_id;

        //simpan data slip penggajian
        $slip_penggajian = SlipPenggajian::create([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'total_gaji' => $request->total_gaji,
            'total_potongan' => $request->total_potongan,
            'total_terima' => $request->take_home_pay,
            'employee_id' => $employee_id,
        ]);

        //ambil id slip penggajian
        $slip_penggajian_id = $slip_penggajian->id;

        // Simpan data slip gaji
        foreach ($request->gaji as $id => $nominal) {
            RincianSlipGaji::create([
                'nama_gaji' => $request->nama_gaji[$id] ?? '',
                'nominal_gaji' => $nominal,
                'slip_penggajian_id' => $slip_penggajian_id,
            ]);
        }

        // Simpan data slip potongan
        // buat kondisi agar tidak ada potongan maka langsung dilewati saja
        if ($request->potongan == null) {
            //return kembalikan ke halaman create slip gaji
            return redirect()->route('slip_gaji.create', [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'employee_id' => $employee_id,
            ]);
        }else{
            foreach ($request->potongan as $id => $nominal) {
                RincianSlipPotongan::create([
                    'nama_potongan' => $request->nama_potongan[$id] ?? '',
                    'nominal_potongan' => $nominal,
                    'slip_penggajian_id' => $slip_penggajian_id,
                ]);
            }
        }

        //return kembalikan ke halaman create slip gaji
        return redirect()->route('slip_gaji.create', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'employee_id' => $employee_id,
        ]);
    }
    public function storeAllSlip(Request $request)
    {
        // dd($request->all());
        // Validasi request
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:' . date('Y'),
            'selected_employees' => 'required|array',
            'selected_employees.*' => 'exists:employees,id',
            'gaji' => 'required|array',
            'gaji.*.*' => 'required|numeric|min:0',
            'potongan' => 'nullable|array',
            'potongan.*.*' => 'nullable|numeric|min:0',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $selectedEmployees = $request->selected_employees;

        $skippedEmployees = []; // Untuk mencatat karyawan yang datanya belum tersetting atau sudah punya slip

        foreach ($selectedEmployees as $employee_id) {

            // Cek apakah slip gaji sudah pernah dibuat untuk karyawan ini
            $existingSlip = SlipPenggajian::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->where('employee_id', $employee_id)
                ->exists();

            if ($existingSlip) {
                // Jika slip sudah ada, catat di array skipped dan lanjutkan
                $skippedEmployees[] = "Karyawan ID $employee_id sudah memiliki slip gaji.";
                continue;
            }

            // Cek apakah data gaji karyawan sudah tersetting
            if (empty($request->gaji[$employee_id]) || array_sum($request->gaji[$employee_id]) <= 0) {
                $skippedEmployees[] = "Karyawan ID $employee_id belum memiliki data gaji yang valid.";
                continue;
            }

            // Hitung total gaji dan total potongan
            $total_gaji = array_sum($request->gaji[$employee_id] ?? []);
            $total_potongan = array_sum($request->potongan[$employee_id] ?? []);
            $take_home_pay = $total_gaji - $total_potongan;

            // Simpan data slip penggajian
            $slip_penggajian = SlipPenggajian::create([
                'bulan' => $bulan,
                'tahun' => $tahun,
                'total_gaji' => $total_gaji,
                'total_potongan' => $total_potongan,
                'total_terima' => $take_home_pay,
                'employee_id' => $employee_id,
            ]);

            // Simpan rincian gaji
            foreach ($request->gaji[$employee_id] ?? [] as $id => $nominal) {
                RincianSlipGaji::create([
                    'nama_gaji' => $request->nama_gaji[$id] ?? '',
                    'nominal_gaji' => $nominal,
                    'slip_penggajian_id' => $slip_penggajian->id,
                ]);
            }

            // Simpan rincian potongan jika ada
            if (!empty($request->potongan[$employee_id])) {
                foreach ($request->potongan[$employee_id] as $id => $nominal) {
                    RincianSlipPotongan::create([
                        'nama_potongan' => $request->nama_potongan[$id] ?? '',
                        'nominal_potongan' => $nominal,
                        'slip_penggajian_id' => $slip_penggajian->id,
                    ]);
                }
            }
        }

        // Jika ada karyawan yang tidak bisa diproses, tampilkan pesan error
        if (!empty($skippedEmployees)) {
            return redirect()->route('slip_gaji.index')->with('error', implode('<br>', $skippedEmployees));
        }

        return redirect()->route('slip_gaji.index')->with('success', 'Slip gaji berhasil disimpan untuk karyawan yang memenuhi syarat.');
    }

    public function sendSlip(Request $request)
    {
        $bulan                  = $request->input('bulan', date('m'));
        $tahun                  = $request->input('tahun', date('Y'));
        $SlipPenggajians        = SlipPenggajian::where('bulan', $bulan)->where('tahun', $tahun)->get();
        $rincianslipgaji        = RincianSlipGaji::all();
        $rincianslippotongan    = RincianSlipPotongan::all();
        return view('keuangan.slip_gaji.senderslip', compact('rincianslipgaji', 'rincianslippotongan', 'employees', 'SlipPenggajians', 'bulan', 'tahun'));
    }

    public function CetakSlipPenggajian(Request $request, $id, $bulan, $tahun)
    {
        // Ambil data slip penggajian
        $slip_penggajian = SlipPenggajian::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('employee_id', $id)
            ->firstOrFail();
        
        $rincianslipgaji = RincianSlipGaji::where('slip_penggajian_id', $slip_penggajian->id)->get();
        $rincianslippotongan = RincianSlipPotongan::where('slip_penggajian_id', $slip_penggajian->id)->get();
        $employee = Employee::with('statusKaryawan', 'unit')->where('id', $id)->first();

        $path = public_path('rsia.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // Load tampilan Blade sebagai HTML
        $html = view('keuangan.slip_gaji.cetakpdf', compact(
            'slip_penggajian',
            'rincianslipgaji',
            'rincianslippotongan',
            'employee',
            'logo'
        ))->render();

        // Konfigurasi Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isHtml5ParserEnabled', true);

        // Inisialisasi Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Stream PDF ke browser
        return $dompdf->stream('slip_gaji_'.$employee->id.'_'.$bulan.'_'.$tahun.'.pdf', ['Attachment' => false]);
    }
}
