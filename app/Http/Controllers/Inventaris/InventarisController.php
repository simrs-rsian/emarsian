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

    public function indexChecker(Request $request)
    {
        $id_ruang = $request->input('id_ruang');

        $datas = null;
        if ($id_ruang) {
            $datas = DB::connection('mysql2')->table('v_kartuinventaris')->where('id_ruang', $id_ruang)->get();
        }

        return view('inventaris.indexChecker', compact('datas'));
    }

    public function showChecker($id){
        $ruangs = DB::connection('mysql2')->table('inventaris_ruang')->where('id_ruang', $id)->first();
        $inventaris  = DB::connection('mysql2')->table('v_kartuinventaris')->where('id_ruang', $id)->get();
        return view('inventaris/showChecker', compact('inventaris', 'ruangs'));
    }
}
