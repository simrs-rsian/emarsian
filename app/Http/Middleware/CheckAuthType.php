<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $type)
    {
        if ($type === 'admin') {
            if (!Auth::guard('web')->check()) {
                abort(403, 'Hanya admin yang bisa mengakses.');
            }
        } elseif ($type === 'pegawai') {
            if (!Auth::guard('pegawai')->check()) {
                abort(403, 'Hanya pegawai yang bisa mengakses.');
            }
        } else {
            abort(403, 'Guard tidak dikenali.');
        }

        return $next($request);
    }
}
