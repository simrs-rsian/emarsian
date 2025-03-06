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
use App\Models\Keuangan\HistorySenderSlip;
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

    public function IndexSendSlip(Request $request)
    {
        $bulan                  = $request->input('bulan', date('m'));
        $tahun                  = $request->input('tahun', date('Y'));
        $SlipPenggajians        = SlipPenggajian::where('bulan', $bulan)->where('tahun', $tahun)->get();
        $rincianslipgaji        = RincianSlipGaji::with('slip_penggajian')->get();
        $rincianslippotongan    = RincianSlipPotongan::with('slip_penggajian')->get();
        $employees              = Employee::all();
        return view('keuangan.slip_gaji.indexsenderslip', compact('rincianslipgaji', 'rincianslippotongan', 'employees', 'SlipPenggajians', 'bulan', 'tahun'));
    }

    public function SendSlip(Request $request, $id, $bulan, $tahun)
    {
        $employee = Employee::findOrFail($id);
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Generate link slip gaji
        $link = url('keuangan/slip_gaji/slip-gaji-karyawan?id_pegawai=' . base64_encode($id) . '&bulan=' . base64_encode($bulan) . '&tahun=' . base64_encode($tahun));

        // Pesan WhatsApp
        $pesan = 'Assalamualaikum Warohmatullahi Wabarokatuh ' . "\n\n" .
                'Kami dari Admin Keuangan Rumah Sakit Aisyiah Nganjuk ingin menginformasikan bahwa ' .
                'pegawai atas nama ' . $employee->nama_lengkap . ' telah menerima slip gaji untuk bulan ' . $bulan . ' tahun ' . $tahun . '. ' . "\n\n" .
                'Berikut kami lampirkan slip gaji Anda untuk bulan ' . $bulan . ' tahun ' . $tahun . '. ' . "\n\n" .
                'Link untuk mengunduh slip gaji Anda: ' . $link . "\n\n" .
                'Mohon untuk melakukan koreksi jika ada kekeliruan dalam dokumentasi kami. ' . "\n\n" . 
                'Terima kasih atas kepercayaan yang Anda berikan. ' . "\n\n" . 
                'Semoga Allah selalu memberikan kesehatan kepada Anda. Aamiin Ya Robbal Aalamiin.';

        // Kirim pesan WhatsApp menggunakan service provider
        $phone = $employee->telepon;
        $response = app('WaHelper')->sendMessage($phone, $pesan);

        $idslip = SlipPenggajian::where('employee_id', $id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
        // Simpan riwayat pengiriman
        HistorySenderSlip::create([
            'slip_penggajian_id' => $idslip->id,
            'user_id' => auth()->id(),
            'status' => 'Sukses',
            'message' => $pesan,
            'link' => $link,
            'status_downloader' => '1',
        ]);

        //tampilkan pesan sukses ke halaman sebelumnya
        return redirect()->route('slip_gaji.IndexSendSlip', [
            'bulan' => $bulan,
            'tahun' => $tahun,
        ])->with('success', 'Slip gaji berhasil dikirim via WhatsApp');
    }

    public function SendAllSlip(Request $request)
    {
        $selectedEmployees = $request->input('selected_employees', []);
        $employeeData = $request->input('employee_data', []);
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        if (empty($selectedEmployees)) {
            return redirect()->back()->with('error', 'Tidak ada karyawan yang dipilih.');
        }

        $messages = [];
        foreach ($selectedEmployees as $id) {
            if (!isset($employeeData[$id])) {
                continue;
            }

            $employee = Employee::find($id);
            if (!$employee) {
                continue;
            }

            $link = url('keuangan/slip_gaji/slip-gaji-karyawan?id_pegawai=' . base64_encode($id) . '&bulan=' . base64_encode($bulan) . '&tahun=' . base64_encode($tahun));

            $pesan = "Assalamualaikum Warohmatullahi Wabarokatuh\n\n"
                . "Kami dari Admin Keuangan Rumah Sakit Aisyiah Nganjuk ingin menginformasikan bahwa "
                . "pegawai atas nama {$employee->nama_lengkap} telah menerima slip gaji untuk bulan {$bulan} tahun {$tahun}.\n\n"
                . "Berikut kami lampirkan slip gaji Anda untuk bulan {$bulan} tahun {$tahun}.\n\n"
                . "Link untuk mengunduh slip gaji Anda: {$link}\n\n"
                . "Mohon untuk melakukan koreksi jika ada kekeliruan dalam dokumentasi kami.\n\n"
                . "Terima kasih atas kepercayaan yang Anda berikan.\n\n"
                . "Semoga Allah selalu memberikan kesehatan kepada Anda. Aamiin Ya Robbal Aalamiin.";

            

            $phone = $employeeData[$id]['telepon'];
            $messages[] = [
                'target' => $phone,
                'message' => $pesan,
                'delay' => '2',
                'countryCode' => '62',
                'typing' => false,
                'delay' => '2',
            ];
            //id slip penggajian
            $idslip = SlipPenggajian::where('employee_id', $id)->where('bulan', $bulan)->where('tahun', $tahun)->first();

            // Simpan riwayat pengiriman
            HistorySenderSlip::create([
                'slip_penggajian_id' => $idslip,
                'user_id' => auth()->id(),
                'status' => 'success',
                'message' => $pesan,
                'link' => $link,
                'status_downloader' => 'open downloaded',
            ]);
            
        }

        if (!empty($messages)) {
            app('WaBroadcastHelper')->sendBatchMessages($messages);
        }

        return redirect()->route('slip_gaji.IndexSendSlip', [
            'bulan' => $bulan,
            'tahun' => $tahun,
        ])->with('success', 'Slip gaji berhasil dikirim ke semua karyawan yang dipilih.');
    }

    public function slipGajiKaryawan(Request $request)
    {
        $validatelink = url('keuangan/slip_gaji/slip-gaji-karyawan?id_pegawai=' . $request->input('id_pegawai') . '&bulan=' . $request->input('bulan') . '&tahun=' . $request->input('tahun'));
    
        // Validasi link hanya bisa dipakai 1 kali
        $history = HistorySenderSlip::where('link', $validatelink)->first();
    
        if (!$history || $history->status_downloader !== '1') {
            return response()->json(['message' => 'Link tidak valid atau sudah pernah digunakan.'], 404);
        }
    
        // Decrypt parameters from the request
        $id = base64_decode($request->input('id_pegawai'));
        $bulan = base64_decode($request->input('bulan'));
        $tahun = base64_decode($request->input('tahun'));
    
        // Cek jika session sudah pernah digunakan
        if (session()->has('id') && session()->has('bulan') && session()->has('tahun')) {
            return redirect()->route('slip_gaji.IndexSendSlip', [
                'bulan' => session()->get('bulan'),
                'tahun' => session()->get('tahun'),
            ]);
        }
    
        // Ambil data slip penggajian
        $slip_penggajian = SlipPenggajian::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('employee_id', $id)
            ->firstOrFail();
    
        $rincianslipgaji = RincianSlipGaji::where('slip_penggajian_id', $slip_penggajian->id)->get();
        $rincianslippotongan = RincianSlipPotongan::where('slip_penggajian_id', $slip_penggajian->id)->get();
        $employee = Employee::with('statusKaryawan', 'unit')->where('id', $id)->first();
    
        // Load logo
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
    
        // Update status history setelah slip berhasil dibuat
        $history->status_downloader = 'close downloaded';
        $history->save();
    
        // Stream PDF ke browser
        return $dompdf->stream('slip_gaji_' . $employee->id . '_' . $bulan . '_' . $tahun . '.pdf');
    }
}
