<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\DefaultGaji;
use App\Models\Keuangan\ModeGaji;
use Illuminate\Http\Request;

class DefaultGajiController extends Controller
{
    public function index()
    {
        $defaultGajis = DefaultGaji::with('mode')->get();
        $modeGajis = ModeGaji::all();
        return view('keuangan.defaultgaji', compact('defaultGajis', 'modeGajis'));
    }

    public function create()
    {
        return view('default_gaji.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'gaji_nama' => 'required|array',
            'mode_id' => 'required|array|exists:mode_gajis,id',
        ]);

        if (count($request->gaji_nama) !== count($request->mode_id)) {
            return redirect()->back()->withErrors('The number of gaji_nama and mode_id must be the same.');
        }

        foreach ($request->gaji_nama as $index => $gaji_nama) {
            DefaultGaji::create([
                'gaji_nama' => $gaji_nama,
                'mode_id' => $request->mode_id[$index],
            ]);
        }

        return redirect()->route('default_gaji.index')->with('success', 'Default Gaji created successfully.');
    }

    public function edit(defaultGaji $defaultGaji)
    {
        return view('default_gaji.edit', compact('defaultGaji'));
    }

    public function update(Request $request, defaultGaji $defaultGaji)
    {
        $request->validate([
            'gaji_nama' => 'required|string|max:255',
            'mode_id' => 'required|exists:mode_gajis,id',
        ]);

        $defaultGaji->update($request->all());

        return redirect()->route('default_gaji.index')->with('success', 'Default Gaji updated successfully.');
    }

    public function destroy(defaultGaji $defaultGaji)
    {
        $defaultGaji->delete();

        return redirect()->route('default_gaji.index')->with('success', 'Default Gaji deleted successfully.');
    }
}
