<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AdminAuthController extends Controller
{
    public function login() {
        if (Auth::check()){
            return redirect('dashboard');
        }else {
            return view('auth/adminauth');
        }
    }

    public function actionlogin(Request $request){
        $username = $request->input('username');
        $password = md5($request->input('password')); // Mengenkripsi password menggunakan md5
    
        // Periksa apakah username ditemukan di database
        $user = User::where('username', $username)->first();

        if ($user) {
            // Periksa apakah password cocok
            if ($user->password === $password) {
                // Autentikasi berhasil, login pengguna
                Auth::login($user);

                // Set session data jika diperlukan
                session(['user_id' => $user->id]);
                session(['user_name' => $user->username]);
                session(['user_fullname' => $user->fullname]);

                return redirect('dashboard');
            }
        }
    
        // Autentikasi gagal, tampilkan pesan kesalahan
        Session::flash('error', 'Email atau Password Salah');
        return redirect('/');
    }
    

    public function actionlogout() {
        Auth::logout();
        return redirect('/');
    }
}
