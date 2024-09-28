<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Profesi;

class ProfesiController extends Controller
{
    public function index()
    {
        $profesis = Profesi::all();
        return view('master.profesi', compact('profesis'));
    }

    public function create()
    {
        return view('master.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_profesi' => 'required|array',
            'nama_profesi.*' => 'required|string|max:255',
        ]);

        foreach ($request->nama_profesi as $profesi) {
            Profesi::create(['nama_profesi' => $profesi]);
        }

        return redirect()->route('profesi.index')->with('success', 'profesis created successfully.');
    }

    public function edit(profesi $profesi)
    {
        return view('master.edit', compact('profesi'));
    }

    public function update(Request $request, profesi $profesi)
    {
        $request->validate([
            'nama_profesi' => 'required|string|max:255',
        ]);

        $profesi->update($request->all());

        return redirect()->route('profesi.index')->with('success', 'profesi updated successfully.');
    }

    public function destroy(profesi $profesi)
    {
        $profesi->delete();

        return redirect()->route('profesi.index')->with('success', 'profesi deleted successfully.');
    }
}
