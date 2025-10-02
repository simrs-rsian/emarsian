<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDefaultPassword
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('pegawai')->user();

        // Cek jika pengguna login dan password-nya adalah MD5('123456')
        if ($user && $user->password === md5('123456')) {
            // Jika sedang tidak di halaman ganti password, arahkan ke halaman itu
            if (!$request->is('pegawai/ganti-password')) {
                return redirect()->route('pegawai.ganti_password');
            }
        }

        return $next($request);
    }
}
