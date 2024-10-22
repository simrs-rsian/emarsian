<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('setting.role', compact('roles'));
    }

    public function create()
    {
        // return view('setting.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|array',
            'nama_role.*' => 'required|string|max:255',
        ]);

        foreach ($request->nama_role as $role) {
            Role::create(['nama_role' => $role]);
        }

        return redirect()->route('role.index')->with('success', 'roles created successfully.');
    }

    public function edit(Role $role)
    {
        return view('setting.edit', compact('role'));
    }

    public function update(Request $request, role $role)
    {
        $request->validate([
            'nama_role' => 'required|string|max:255',
        ]);

        $role->update($request->all());

        return redirect()->route('role.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('role.index')->with('success', 'Role deleted successfully.');
    }
}
