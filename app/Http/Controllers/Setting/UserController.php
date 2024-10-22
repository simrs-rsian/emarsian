<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting\User;
use App\Models\Setting\Role;

class UserController extends Controller
{
    // Menampilkan semua user
    public function index()
    {
        $users = User::select('users.*', 'roles.nama_role')
                ->join('roles', 'users.role', 'roles.id')
                ->get();
        $roles = Role::all();
        return view('setting.user', compact('users', 'roles'));
    }

    public function show($id){ 
        $userset = User::select('users.*', 'roles.nama_role')
                            ->join('roles', 'users.role', '=', 'roles.id')
                            ->where('users.id', $id)  // Filter by the employee ID
                            ->first();  // Mengambil satu data
        return view('setting.show', compact('userset'));        
    }

    // Menampilkan form untuk membuat user baru
    public function create()
    {
        return view('setting.user.create');
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users,username',
            'password' => 'required|string|min:6',
            'role' => 'required|string|max:191',
        ]);

        // Simpan user baru dengan password MD5
        User::create([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'password' => md5($request->password), // Hash password dengan MD5
            'role' => $request->role,
        ]);

        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    // Menampilkan form edit user berdasarkan ID
    public function edit(User $user)
    {
        return view('setting.user.edit', compact('user'));
    }

    // Mengupdate data user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'fullname' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users,username,' . $user->id,
            'password' => 'sometimes|nullable|string|min:6',
            'role' => 'required|string|max:191',
        ]);

        // Ambil password lama dari database
        $currentPassword = $user->password;

        // Cek apakah password yang diinput berbeda dari password di database
        $newPassword = $request->password ? md5($request->password) : $currentPassword;

        // Update data user
        $user->update([
            'fullname' => $request->fullname,
            'username' => $request->username,
            // Update password hanya jika berbeda dari password lama
            'password' => $newPassword !== $currentPassword ? $newPassword : $currentPassword,
            'role' => $request->role,
        ]);

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    // Menghapus user
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }
}
