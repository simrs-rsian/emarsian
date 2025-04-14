<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Setting\WebSetting as SettingWeb;

class InventarisController extends Controller
{
    public function index(){
        $datas = DB::connection('mysql2')->table('inventaris_ruang')->get();
        return view('inventaris/index', compact('datas'));
    }

    public function show($id){
        $ruangs = DB::connection('mysql2')->table('inventaris_ruang')->where('id_ruang', $id)->first();
        $inventaris  = DB::connection('mysql2')->table('v_kartuinventaris')->where('id_ruang', $id)->get();
        return view('inventaris/show', compact('inventaris', 'ruangs'));
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

        return view('inventaris.indexChecker', compact('datas', 'message'));
    }


    public function showChecker($id){
        $ruangs = DB::connection('mysql2')->table('inventaris_ruang')->where('id_ruang', $id)->first();
        $inventaris  = DB::connection('mysql2')->table('v_kartuinventaris')->where('id_ruang', $id)->get();
        return view('inventaris/showChecker', compact('inventaris', 'ruangs'));
    }
}
