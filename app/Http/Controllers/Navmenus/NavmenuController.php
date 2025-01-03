<?php

namespace App\Http\Controllers\Navmenus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting\Navmenus;
use App\Models\Setting\Role;
use App\Models\Setting\HakAkses;

class NavmenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil main menu (menu tanpa parent) yang diakses pengguna
        $mainmenus = Navmenus::where('m_child', 0)
            ->where('m_status', 1)
            ->orderBy('m_order', 'asc')
            ->get();

        // Mengambil submenu (menu dengan parent) yang diakses pengguna
        $submenus = Navmenus::where('m_child', '!=', 0)
            ->where('m_status', 1)
            ->orderBy('m_order', 'asc')
            ->get();

        $roles = Role::all();
        return view('navmenu.index', compact('roles', 'mainmenus', 'submenus'));
    }

    public function getNavmenu($roleId)
    {
        // Ambil semua menu
        $navmenus = Navmenus::all();

        // Ambil semua menu ID yang terkait dengan role
        $checkedMenuIds = Hakakses::where('role_id', $roleId)
            ->pluck('navmenu_id')
            ->toArray();

        // Tandai menu dengan checked status
        $navmenus = $navmenus->map(function ($menu) use ($checkedMenuIds) {
            $menu->checked = in_array($menu->m_id, $checkedMenuIds);
            return $menu;
        });

        return response()->json(['navmenus' => $navmenus]);
    }

    public function updateHakakses(Request $request)
    {
        $validatedData = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'menu_id' => 'required|exists:navmenus,m_id',
            'checked' => 'required|boolean',
        ]);

        $roleId = $validatedData['role_id'];
        $menuId = $validatedData['menu_id'];
        $checked = $validatedData['checked'];

        if ($checked) {
            // Jika checked, tambahkan data ke tabel hak_akses
            HakAkses::updateOrCreate(
                [   
                    'role_id' => $roleId, 
                    'navmenu_id' => $menuId
                ],
                []
            );
        } else {
            // Jika unchecked, hapus data dari tabel hak_akses
            HakAkses::where('role_id', $roleId)
                ->where('navmenu_id', $menuId)
                ->delete();
        }

        return response()->json(['message' => 'Hak akses berhasil diperbarui.']);
    }


    public function save(Request $request)
    {
        $roleId = $request->input('role_id');
        $selectedNavmenu = $request->input('Navmenus', []);

        // Hapus semua akses sebelumnya
        HakAkses::where('role_id', $roleId)->delete();

        // Tambahkan akses baru
        foreach ($selectedNavmenu as $NavmenuId) {
            HakAkses::create([
                'role_id' => $roleId,
                'Navmenus_id' => $NavmenuId,
            ]);
        }

        return redirect()->route('Navmenus.index')->with('success', 'Akses menu berhasil diperbarui.');
    }
}
