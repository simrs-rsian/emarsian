<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Models\Riwayat\RiwayatKeluarga;
use App\Models\Riwayat\RiwayatPelatihan ;
use Illuminate\Http\Request;

class DashboardEmployeeController extends Controller
{
    public function index() {
        $sessionId = session('id');
        $employee = Employee::where('id', $sessionId)->first();
        $riwayatKeluarga = RiwayatKeluarga::where('id_employee', $sessionId)->get();
        $totalRiwayatKeluarga = $riwayatKeluarga->count();
        $totalRiwayatPelatihan = RiwayatPelatihan::where('id_employee', $sessionId)->count();
        $totalSemua = $totalRiwayatKeluarga + $totalRiwayatPelatihan;
        return view('dashboard/dashboardEmployee', compact('employee', 'totalRiwayatKeluarga', 'totalRiwayatPelatihan', 'totalSemua'));
    }
}
