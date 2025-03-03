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

        // Mengambil subsubmenu (child dari submenu)
        $subsubmenus = Navmenus::whereIn('m_child', $submenus->pluck('m_id'))
            ->where('m_status', 1)
            ->orderBy('m_order', 'asc')
            ->get();

        $roles = Role::all();
        return view('navmenu.index', compact('roles', 'mainmenus', 'submenus', 'subsubmenus'));
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

    public function store(Request $request) {
        $validatedData = $request->validate([
            'm_name' => 'required|string|max:255',
            'm_link' => 'required|string|max:255',
            'm_icon' => 'required|string|max:255',
            'm_child' => 'required|integer',
            'm_order' => 'required|integer',
            'm_status' => 'required|integer',
        ]);

        Navmenus::create([
            'm_name' => $validatedData['m_name'],
            'm_link' => $validatedData['m_link'],
            'm_icon' => $validatedData['m_icon'],
            'm_child' => $validatedData['m_child'],
            'm_order' => $validatedData['m_order'],
            'm_status' => $validatedData['m_status'],
        ]);

        return redirect()->route('navmenu.indexmenu')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(Request $request) {
        dd('update');
        dd($request->all());
        $menu = Navmenus::findOrFail($id);

        $validatedData = $request->validate([
            'm_name' => 'required|string|max:255',
            'm_link' => 'required|string|max:255',
            'm_icon' => 'required|string|max:255',
            'm_child' => 'required|integer',
            'm_order' => 'required|integer',
            'm_status' => 'required|integer',
        ]);

        $menu->update($validatedData);

        return redirect()->route('navmenu.indexmenu')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy($id) {
        dd($id);
        $menu = Navmenus::findOrFail($id);
        $menu->delete();

        return redirect()->route('navmenu.indexmenu')->with('success', 'Menu berhasil dihapus.');
    }

    public function indexmenu() {
        
        // Mengambil main menu (menu tanpa parent) yang diakses pengguna
        $mainmenus = Navmenus::where('m_child', 0)
        ->orderBy('m_order', 'asc')
        ->get();

        // Mengambil submenu (menu dengan parent) yang diakses pengguna
        $submenus = Navmenus::where('m_child', '!=', 0)
        ->orderBy('m_order', 'asc')
        ->get();

        // Mengambil subsubmenu (child dari submenu)
        $consts = Navmenus::whereIn('m_child', $submenus->pluck('m_id'))
        ->orderBy('m_order', 'asc')
        ->get();

        $roles = Role::all();
        return view('navmenu.indexmenu', compact('roles', 'mainmenus', 'submenus', 'consts'));
        
        
    }
}
