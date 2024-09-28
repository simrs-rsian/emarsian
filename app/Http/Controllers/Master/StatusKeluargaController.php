<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\StatusKeluarga;

class StatusKeluargaController extends Controller
{
    public function index()
    {
        $statuskeluargas = StatusKeluarga::all();
        return view('master.statuskeluarga', compact('statuskeluargas'));
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

        foreach ($request->nama_status as $statuskeluarga) {
            StatusKeluarga::create(['nama_status' => $statuskeluarga]);
        }

        return redirect()->route('statuskeluarga.index')->with('success', 'statuskeluarga created successfully.');
    }

    public function edit(statuskeluarga $statuskeluarga)
    {
        return view('master.edit', compact('statuskeluarga'));
    }

    public function update(Request $request, statuskeluarga $statuskeluarga)
    {
        $request->validate([
            'nama_status' => 'required|string|max:255',
        ]);

        $statuskeluarga->update($request->all());

        return redirect()->route('statuskeluarga.index')->with('success', 'statuskeluarga updated successfully.');
    }

    public function destroy(statuskeluarga $statuskeluarga)
    {
        $statuskeluarga->delete();

        return redirect()->route('statuskeluarga.index')->with('success', 'statuskeluarga deleted successfully.');
    }
}
