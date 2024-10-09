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
    public function login() {
        if (Auth::check()){
            return redirect('dashboardEmployee');
        }else {
            return view('auth/employeeauth');
        }
    }

    public function actionlogin(Request $request){
        $nip_karyawan = $request->input('nip_karyawan');
        $password = md5($request->input('password')); // Mengenkripsi password menggunakan md5
    
        // Periksa apakah username ditemukan di database
        $employee = Employee::where('nip_karyawan', $nip_karyawan)->first();

        if ($employee) {
            // Periksa apakah password cocok
            if ($employee->password === $password) {
                // Autentikasi berhasil, login pengguna
                Auth::login($employee);

                // Set session data jika diperlukan
                session(['id' => $employee->id]);
                session(['nip_pegawai' => $employee->username]);
                session(['nama_lengkap' => $employee->nama_lengkap]);

                return redirect('dashboardEmployee');
            }
        }
    
        // Autentikasi gagal, tampilkan pesan kesalahan
        Session::flash('error', 'Username atau Password Salah');
        return redirect('/');
    }
    

    public function actionlogout() {
        Auth::logout();
        return redirect('/');
    }
}
