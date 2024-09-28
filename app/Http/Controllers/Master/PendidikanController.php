<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Pendidikan;

class PendidikanController extends Controller
{
    public function index()
    {
        $pendidikans = Pendidikan::all();
        return view('master.pendidikan', compact('pendidikans'));
    }

    public function create()
    {
        return view('master.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pendidikan' => 'required|array',
            'nama_pendidikan.*' => 'required|string|max:255',
        ]);

        foreach ($request->nama_pendidikan as $pendidikan) {
            Pendidikan::create(['nama_pendidikan' => $pendidikan]);
        }

        return redirect()->route('pendidikan.index')->with('success', 'pendidikans created successfully.');
    }

    public function edit(pendidikan $pendidikan)
    {
        return view('master.edit', compact('pendidikan'));
    }

    public function update(Request $request, pendidikan $pendidikan)
    {
        $request->validate([
            'nama_pendidikan' => 'required|string|max:255',
        ]);

        $pendidikan->update($request->all());

        return redirect()->route('pendidikan.index')->with('success', 'pendidikan updated successfully.');
    }

    public function destroy(pendidikan $pendidikan)
    {
        $pendidikan->delete();

        return redirect()->route('pendidikan.index')->with('success', 'pendidikan deleted successfully.');
    }
    
}
