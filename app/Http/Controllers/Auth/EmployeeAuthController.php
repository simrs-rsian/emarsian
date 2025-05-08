<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\Session;

class EmployeeAuthController extends Controller
{
    public function employeelogin() {
        if (Auth::check()){
            return redirect('dashboardEmployee');
        }else {
            return view('auth/employeeauth');
        }
    }

    public function actionloginemployee(Request $request)
    {
        $nip_karyawan = $request->input('username');
        $password = md5($request->input('password')); // MD5 hash

        $employee = Employee::where('nip_karyawan', $nip_karyawan)->first();

        if ($employee && $employee->password === $password) {
            // Login menggunakan guard pegawai
            Auth::guard('pegawai')->login($employee);

            // Set session jika diperlukan
            session([
                'id' => $employee->id,
                'nip_pegawai' => $employee->nip_karyawan,
                'nama_lengkap' => $employee->nama_lengkap,
                'navmenu' => 'pegawai',
            ]);

            return redirect('dashboardEmployee');
        }

        // Jika gagal
        Session::flash('error', 'Username atau Password Salah');
        return redirect('/');
    }
    

    public function logoutPegawai() {
        Auth::guard('pegawai')->logout();
        return redirect('/');
    }
}
