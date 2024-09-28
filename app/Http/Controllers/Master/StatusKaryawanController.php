<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\StatusKaryawan;

class StatusKaryawanController extends Controller
{
    public function index()
    {
        $statuskaryawans = StatusKaryawan::all();
        return view('master.statuskaryawan', compact('statuskaryawans'));
    }

    public function create()
    {
        return view('master.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_status' => 'required|array',
            'nama_status.*' => 'required|string|max:255',
        ]);

        foreach ($request->nama_status as $statuskaryawan) {
            StatusKaryawan::create(['nama_status' => $statuskaryawan]);
        }

        return redirect()->route('statuskaryawan.index')->with('success', 'status karyawan created successfully.');
    }

    public function edit(statuskaryawan $statuskaryawan)
    {
        return view('master.edit', compact('statuskaryawan'));
    }

    public function update(Request $request, statuskaryawan $statuskaryawan)
    {
        $request->validate([
            'nama_status' => 'required|string|max:255',
        ]);

        $statuskaryawan->update($request->all());

        return redirect()->route('statuskaryawan.index')->with('success', 'status karyawan updated successfully.');
    }

    public function destroy(statuskaryawan $statuskaryawan)
    {
        $statuskaryawan->delete();

        return redirect()->route('statuskaryawan.index')->with('success', 'status karyawan deleted successfully.');
    }
}
