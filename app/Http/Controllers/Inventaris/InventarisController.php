<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Setting\WebSetting as SettingWeb;
use Illuminate\Support\Carbon;

class InventarisController extends Controller
{
    public function index(){
        $datas = DB::connection('mysql2')->table('inventaris_ruang')->get();
        return view('inventaris/index', compact('datas'));
    }

    public function show($id){
        $ruangs = DB::connection('mysql2')->table('inventaris_ruang')->where('id_ruang', $id)->first();
        $inventaris  = DB::connection('mysql2')->table('v_kartuinventaris')->where('id_ruang', $id)->get();

        $ttd_pj = DB::connection('mysql2')->table('inventaris_sign')->where('id_ruang', $id)->first();

        $QrKasubagRt = null;
        $QrKasubagLu = null;

        if ($ttd_pj) {
            $kataKasubagRt = "Dikeluarkan di RSI Aisyiyah Nganjuk, Kabupaten/Kota Nganjuk\n 
                    Ruangan     : ".$ruangs->nama_ruang."\n
                    Mengetahui Bahwa dengan benar barang tersebut berada dalam ruangan tersebut; \n 
                    Ditandatangani secara elektronik oleh Nita Melina W (Kasubag RT) pada tanggal ".$ttd_pj->tanggal_sign.". \n 
                    di RSI 'Aisyiyah Nganjuk";    
                
            $QrKasubagRt = QrCode::generate($kataKasubagRt);
            $kataKasubagLu = "Dikeluarkan di RSI Aisyiyah Nganjuk, Kabupaten/Kota Nganjuk\n 
                    Ruangan     : ".$ruangs->nama_ruang."\n
                    Mengetahui Bahwa dengan benar barang tersebut berada dalam ruangan tersebut; \n 
                    Ditandatangani secara elektronik oleh Mulia Annisa W (Kasubag Logistik Umum) pada tanggal ".$ttd_pj->tanggal_sign.". \n 
                    di RSI 'Aisyiyah Nganjuk"; 
                
            $QrKasubagLu = QrCode::generate($kataKasubagLu);
        }
        return view('inventaris/show', compact('inventaris', 'ruangs', 'ttd_pj', 'QrKasubagRt', 'QrKasubagLu'));
    }

    public function edit($id){
        $no_inventaris  = str_replace('|', '/', $id);
        $ruangs         = DB::connection('mysql2')->table('inventaris_ruang')->get();
        $barangs        = DB::connection('mysql2')->table('inventaris_barang')->get();
        $inventaris     = DB::connection('mysql2')->table('inventaris')->where('no_inventaris', $no_inventaris)->first();
        return view('inventaris/edit', compact('inventaris', 'ruangs', 'barangs'));
    }

    public function update(Request $request, $id){
        $no_inventaris  = str_replace('|', '/', $id);
        $data = [
            'id_ruang'          => $request->id_ruang,
            'kode_barang'       => $request->kode_barang,
            'asal_barang'       => $request->asal_barang,
            'tgl_pengadaan'     => $request->tgl_pengadaan,
            'harga'             => $request->harga,
            'status_barang'     => $request->status_barang,
            'no_rak'            => $request->no_rak,
            'no_box'            => $request->no_box,
        ];

        DB::connection('mysql2')->table('inventaris')->where('no_inventaris', $no_inventaris)->update($data);
        return redirect()->route('inventaris.show', $request->id_ruang)->with('success', 'Data Inventaris berhasil diupdate');
    }

    public function cetakQrRuang($id){
        $idRuang = $id;

        // Generate QR Code
        $qrCode = QrCode::format('svg')->size(200)->generate($idRuang);        
        $QrRuangs = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

        $path = public_path('logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // Fetch data for the view
        $ruangs = DB::connection('mysql2')->table('inventaris_ruang')->where('id_ruang', $id)->first();
        $inventaris  = DB::connection('mysql2')->table('v_kartuinventaris')->where('id_ruang', $id)->get();
        $settings  = SettingWeb::first();

        $html = view('inventaris/cetakQrRuang', compact('inventaris', 'ruangs', 'QrRuangs', 'settings', 'logo'))->render();
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 360, 432], 'landscape');
        $dompdf->render();

        return $dompdf->stream('QrcodeRuang' . $idRuang . '.pdf', ['Attachment' => false]);
    }

    public function cetakQrRuangBulk(Request $request){
        $selectedIds = $request->input('selected_ids');

        if (!$selectedIds || count($selectedIds) === 0) {
            return redirect()->back()->with('error', 'Tidak ada ruang yang dipilih untuk dicetak.');
        }

        $qrCodes = [];
        $ruangsData = [];
        $settings = SettingWeb::first();

        foreach ($selectedIds as $idRuang) {
            // Generate QR Code
            $qrCode = QrCode::format('svg')->size(200)->generate($idRuang);        
            $QrRuangs = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

            $ruang = DB::connection('mysql2')->table('inventaris_ruang')->where('id_ruang', $idRuang)->first();
            $inventaris = DB::connection('mysql2')->table('v_kartuinventaris')->where('id_ruang', $idRuang)->get();

            $ruangsData[] = [
                'ruang' => $ruang,
                'inventaris' => $inventaris,
                'QrRuangs' => $QrRuangs,
            ];
        }

        $path = public_path('logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $html = view('inventaris/cetakQrRuangBulk', compact('ruangsData', 'settings', 'logo'))->render();
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 360, 432], 'landscape');
        $dompdf->render();

        return $dompdf->stream('QrcodeRuangBulk.pdf', ['Attachment' => false]);
    }

    public function cetakQrBarang($id){
        $idBarang = str_replace('|', '/', $id);

        // Generate QR Code
        $qrCode = QrCode::format('svg')->size(200)->generate($idBarang);        
        $QrBarangs = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

        $path = public_path('logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // Fetch data for the view
        $inventaris = DB::connection('mysql2')->table('v_kartuinventaris')->where('no_inventaris', $idBarang)->first();
        $ruangs = DB::connection('mysql2')->table('inventaris_ruang')->where('id_ruang', $inventaris->id_ruang)->first();
        $settings  = SettingWeb::first();    

        $html = view('inventaris/cetakQrBarang', compact('inventaris', 'ruangs', 'QrBarangs', 'settings', 'logo'))->render();
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 360, 432], 'landscape');
        $dompdf->render();

        return $dompdf->stream('QrcodeBarang' . $idBarang . '.pdf', ['Attachment' => false]);
    }

    public function cetakQrBarangBulk(Request $request){
        // dd($request->all());
        $selectedItems = $request->input('selected_items');

        if (!$selectedItems || count($selectedItems) === 0) {
            return redirect()->back()->with('error', 'Tidak ada barang yang dipilih untuk dicetak.');
        }

        $barangsData = [];
        $settings = SettingWeb::first();

        foreach ($selectedItems as $idBarang) {
            $idBarang = str_replace('|', '/', $idBarang);

            // Generate QR Code
            $qrCode = QrCode::format('svg')->size(200)->generate($idBarang);        
            $QrBarangs = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

            $inventaris = DB::connection('mysql2')->table('v_kartuinventaris')->where('no_inventaris', $idBarang)->first();
            $ruangs = DB::connection('mysql2')->table('inventaris_ruang')->where('id_ruang', $inventaris->id_ruang)->first();

            $barangsData[] = [
                'inventaris' => $inventaris,
                'ruangs' => $ruangs,
                'QrBarangs' => $QrBarangs,
            ];
        }

        $path = public_path('logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $html = view('inventaris/cetakQrBarangBulk', compact('barangsData', 'settings', 'logo'))->render();
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 360, 432], 'landscape');
        $dompdf->render();

        return $dompdf->stream('QrcodeBarangBulk.pdf', ['Attachment' => false]);
    }

    public function indexChecker(Request $request)
    {
        $id = $request->input('kodeQrCode');
        $datas = collect(); // gunakan koleksi kosong sebagai default
        $message = null;

        // Coba cari berdasarkan id_ruang
        $datas = DB::connection('mysql2')->table('v_kartuinventaris')
            ->where('id_ruang', $id)
            ->get();

        // Jika kosong, coba cari berdasarkan no_inventaris
        if ($datas->isEmpty()) {
            $datas = DB::connection('mysql2')->table('v_kartuinventaris')
                ->where('no_inventaris', $id)
                ->get();
        }

        // Jika masih kosong, kirim pesan error
        if ($datas->isEmpty()) {
            $message = "Data tidak ditemukan untuk ID: $id";
        }
        $ruangs = DB::connection('mysql2')->table('inventaris_ruang')->where('id_ruang', $id)->first();

        $ttd_pj = DB::connection('mysql2')->table('inventaris_sign')->where('id_ruang', $id)->first();
        $QrKasubagRt = null;
        $QrKasubagLu = null;

        if (!$ttd_pj) {
            $message = "Data tanda tangan tidak ditemukan untuk ID: $id";
        } elseif ($ruangs) {
            $kataKasubagRt = "Dikeluarkan di RSI Aisyiyah Nganjuk, Kabupaten/Kota Nganjuk\n 
                Ruangan     : " . $ruangs->nama_ruang . "\n
                Mengetahui Bahwa dengan benar barang tersebut berada dalam ruangan tersebut; \n 
                Ditandatangani secara elektronik oleh Nita Melina W (Kasubag RT) pada tanggal " . $ttd_pj->tanggal_sign . ". \n 
                di RSI 'Aisyiyah Nganjuk";

            $QrKasubagRt = QrCode::generate($kataKasubagRt);

            $kataKasubagLu = "Dikeluarkan di RSI Aisyiyah Nganjuk, Kabupaten/Kota Nganjuk\n 
                Ruangan     : " . $ruangs->nama_ruang . "\n
                Mengetahui Bahwa dengan benar barang tersebut berada dalam ruangan tersebut; \n 
                Ditandatangani secara elektronik oleh Mulia Annisa W (Kasubag Logistik Umum) pada tanggal " . $ttd_pj->tanggal_sign . ". \n 
                di RSI 'Aisyiyah Nganjuk";

            $QrKasubagLu = QrCode::generate($kataKasubagLu);
        } else {
            $message = "Data ruangan tidak ditemukan untuk ID: $id";
        }

        return view('inventaris.indexChecker', compact('datas', 'message', 'ruangs', 'ttd_pj', 'QrKasubagRt', 'QrKasubagLu', 'id'));
    }


    public function showChecker($id){
        $ruangs = DB::connection('mysql2')->table('inventaris_ruang')->where('id_ruang', $id)->first();
        $inventaris  = DB::connection('mysql2')->table('v_kartuinventaris')->where('id_ruang', $id)->get();
        return view('inventaris/showChecker', compact('inventaris', 'ruangs'));
    }

    public function storeSign(Request $request){

        $data = $request->input('signature');
        $noRuang = $request->input('no_ruang');
        $nmSign = $request->input('nama_sign');        

        if (strpos($data, 'data:image/png;base64,') !== 0) {
            return response()->json(['success' => false, 'message' => 'Format gambar tidak valid.'], 400);
        }

        $data = str_replace('data:image/png;base64,', '', $data);

        if (!base64_decode($data, true)) {
            return response()->json(['success' => false, 'message' => 'Data base64 tidak valid.'], 400);
        }

        $fileName = 'signature-' . str_replace('/', '_', $noRuang) . '-' . time() . '.png';  
        $filePath = public_path('dokumen/signature/inventaris/') . $fileName;
        
        if (!file_exists(public_path('dokumen/signature/inventaris/'))) {
            if (!mkdir(public_path('dokumen/signature/inventaris/'), 0777, true)) {
            return response()->json(['success' => false, 'message' => 'Gagal membuat direktori untuk menyimpan tanda tangan.'], 500);
            }
        }

        // Decode data base64 dan simpan ke file
        if (file_put_contents($filePath, base64_decode($data)) === false) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan file tanda tangan.'], 500);
        }

        // Cek apakah sudah ada tanda tangan untuk ruang ini
        $existingSign = DB::connection('mysql2')->table('inventaris_sign')->where('id_ruang', $noRuang)->first();

        if ($existingSign) {
            // Hapus file tanda tangan lama jika ada
            $oldFilePath = public_path('dokumen/signature/inventaris/') . $existingSign->signature;
            if (file_exists($oldFilePath)) {
            if (!unlink($oldFilePath)) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus file tanda tangan lama.'], 500);
            }
            }

            // Update tanda tangan jika sudah ada
            $updated = DB::connection('mysql2')->table('inventaris_sign')->where('id_ruang', $noRuang)->update([
            'nama_sign' => $nmSign,
            'signature' => $fileName,
            'tanggal_sign' => Carbon::now(),
            ]);

            if (!$updated) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui data tanda tangan di database.'], 500);
            }
        } else {
            // Simpan data baru jika belum ada
            $inserted = DB::connection('mysql2')->table('inventaris_sign')->insert([
            'id_ruang' => $noRuang,
            'nama_sign' => $nmSign,
            'signature' => $fileName,
            'tanggal_sign' => Carbon::now(),
            ]);

            if (!$inserted) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data ke database.'], 500);
            }
        }
        
        // Jika semua berhasil
        return response()->json(['success' => true, 'file' => $fileName]);
    }
}
