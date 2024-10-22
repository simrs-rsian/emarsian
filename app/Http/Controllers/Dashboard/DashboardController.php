<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        $data = User::latest()->paginate();

        $tetapCount = DB::table('employees')
            ->select(DB::raw('COUNT(id) as total_employees'))
            ->where('status_karyawan', '=', '1')
            ->first();

        $kontrakCount = DB::table('employees')
            ->select(DB::raw('COUNT(id) as total_employees'))
            ->whereIn('status_karyawan', ['2', '3', '4']) // Kondisi whereIn untuk beberapa nilai
            ->first();

        $allCount = DB::table('employees')
            ->select(DB::raw('COUNT(id) as total_employees'))
            ->first();

        $unitsCount = DB::table('units as u')
            ->leftJoin('employees as e', 'u.id', '=', 'e.jabatan_struktural')
            ->select('u.nama_unit', DB::raw('COUNT(e.id) as total_employees'))
            ->groupBy('u.nama_unit')
            ->get();

        $unitsJkCount = DB::table('units as u')
            ->leftJoin('employees as e', 'u.id', '=', 'e.jabatan_struktural')
            ->select('u.nama_unit', 
                DB::raw("SUM(CASE WHEN e.jenis_kelamin = 'L' THEN 1 ELSE 0 END) as total_laki_laki"),
                DB::raw("SUM(CASE WHEN e.jenis_kelamin = 'P' THEN 1 ELSE 0 END) as total_perempuan")
            )
            ->groupBy('u.nama_unit')
            ->get();

        $golonganCount = DB::table('golongans as g')
            ->leftJoin('employees as e', 'g.id', '=', 'e.golongan')
            ->select('g.nama_golongan', 
                DB::raw('COUNT(e.id) as total_golongan'))
            ->groupBy('g.nama_golongan')
            ->get();

        $statusKaryawanCount = DB::table('status_karyawans as s')
            ->leftJoin('employees as e', 's.id', '=', 'e.status_karyawan')
            ->select('s.nama_status', 
                DB::raw('COUNT(e.id) as total_status_karyawan'))
            ->groupBy('s.nama_status')
            ->get();

        $bagians = DB::table('bagians')
            ->select('bagians.*')
            ->get();
        $totalKaryawanKeseluruhan = 0;
        // Iterasi setiap bagian dan hitung total karyawan per profesi
        foreach ($bagians as $bagian) {
            // Ambil profesi berdasarkan id_bagians
            $profesis = DB::table('profesis')
                ->where('id_bagians', $bagian->id)
                ->get();

            $totalEmployeesInBagian = 0;

            foreach ($profesis as $profesi) {
                // Hitung total karyawan berdasarkan jabatan_struktural
                $totalEmployeesInProfesi = DB::table('employees')
                    ->where('jabatan_struktural', $profesi->id)
                    ->count();

                $profesi->total_employees = $totalEmployeesInProfesi;
                $totalEmployeesInBagian += $totalEmployeesInProfesi;
            }

            // Menambahkan profesi dan total karyawan ke dalam objek bagian
            $bagian->profesis = $profesis;
            $bagian->total_employees = $totalEmployeesInBagian;
            $totalKaryawanKeseluruhan += $totalEmployeesInBagian; 
        }        

        $kelompokUmurCount = DB::table('kelompok_umurs as u')
            ->leftJoin('employees as e', 'u.id', '=', 'e.kelompok_usia')
            ->select('u.nama_kelompok', 
                DB::raw('COUNT(e.id) as total_kelompok_umur'))
            ->groupBy('u.nama_kelompok')
            ->get();

        return view('dashboard/dashboard', compact('data','unitsCount','unitsJkCount','golonganCount','statusKaryawanCount','bagians', 'totalKaryawanKeseluruhan', 'kelompokUmurCount', 'tetapCount', 'kontrakCount', 'allCount'));
        // return view('dashboard');
    }
}
