<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Unit;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('master.unit', compact('units'));
    }

    public function create()
    {
        return view('master.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_unit' => 'required|array',
            'nama_unit.*' => 'required|string|max:255',
        ]);

        foreach ($request->nama_unit as $unit) {
            Unit::create(['nama_unit' => $unit]);
        }

        return redirect()->route('unit.index')->with('success', 'Units created successfully.');
    }

    public function edit(Unit $unit)
    {
        return view('master.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'nama_unit' => 'required|string|max:255',
        ]);

        $unit->update($request->all());

        return redirect()->route('unit.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('unit.index')->with('success', 'Unit deleted successfully.');
    }
}
