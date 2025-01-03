<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DynamicRoleAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Rute yang dikecualikan
        $excludedRoutes = [
            'navmenu/get-navmenu/*', // Rute dengan parameter roleId
            'navmenu/update-hakakses',
            // Tambahkan rute lainnya yang perlu dikecualikan
        ];

        // Cek apakah rute saat ini ada dalam pengecualian
        foreach ($excludedRoutes as $excludedRoute) {
            if (fnmatch($excludedRoute, $request->path())) {
                return $next($request); // Jika cocok, lanjutkan
            }
        }

        $role_id = session('role'); // Ambil role pengguna dari session
        $currentUrl = trim($request->path(), '/'); // Dapatkan URL tanpa domain

        // Ambil menu yang diakses berdasarkan role
        $accessibleMenus = DB::table('hak_akses')
            ->join('navmenus', 'hak_akses.navmenu_id', '=', 'navmenus.m_id')
            ->where('hak_akses.role_id', $role_id)
            ->where('navmenus.m_status', 1)
            ->get(['navmenus.m_id', 'navmenus.m_link']);

        // Periksa apakah URL saat ini cocok dengan menu yang dapat diakses
        foreach ($accessibleMenus as $menu) {
            // Gunakan pola pencocokan untuk fleksibilitas
            if (preg_match("#^" . preg_quote($menu->m_link, '#') . "(/.*)?$#", $currentUrl)) {
                return $next($request); // Izinkan akses jika cocok
            }

            // Ambil submenu yang terkait dengan menu utama (berdasarkan m_child = $menu->m_id)
            $subaccessibleMenus = DB::table('navmenus')
                ->where('m_status', 2) // Pastikan status aktif 2 untuk submenu bayangan
                ->where('m_child', $menu->m_id) // Cek parent-child relationship
                ->pluck('m_link')
                ->toArray();

            foreach ($subaccessibleMenus as $submenuLink) {
                if (preg_match("#^" . preg_quote($submenuLink, '#') . "(/.*)?$#", $currentUrl)) {
                    return $next($request); 
                }
            }
        }

        // Redirect jika akses ditolak
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
