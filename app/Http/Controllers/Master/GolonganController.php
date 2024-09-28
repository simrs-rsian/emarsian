<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Golongan;

class GolonganController extends Controller
{
    
    public function index()
    {
        $golongans = Golongan::all();
        return view('master.golongan', compact('golongans'));
    }

    public function create()
    {
        return view('master.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_golongan' => 'required|array',
            'nama_golongan.*' => 'required|string|max:255',
        ]);

        foreach ($request->nama_golongan as $golongan) {
            Golongan::create(['nama_golongan' => $golongan]);
        }

        return redirect()->route('golongan.index')->with('success', 'golongans created successfully.');
    }

    public function edit(golongan $golongan)
    {
        return view('master.edit', compact('golongan'));
    }

    public function update(Request $request, golongan $golongan)
    {
        $request->validate([
            'nama_golongan' => 'required|string|max:255',
        ]);

        $golongan->update($request->all());

        return redirect()->route('golongan.index')->with('success', 'golongan updated successfully.');
    }

    public function destroy(golongan $golongan)
    {
        $golongan->delete();

        return redirect()->route('golongan.index')->with('success', 'golongan deleted successfully.');
    }
}
