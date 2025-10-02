<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Profesi;
use Illuminate\Support\Facades\DB;

class ProfesiController extends Controller
{
    public function index()
    {
        $profesis = Profesi::select('profesis.id', 'nama_profesi', 'id_bagians', 'nama_bagian')
            ->leftJoin('bagians', 'profesis.id_bagians', '=', 'bagians.id')
            ->get();
        $bagians = DB::table('bagians')->get();
        return view('master.profesi', compact('profesis', 'bagians'));
    }

    public function create()
    {
        return view('master.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_profesi' => 'required|array',
            'id_bagians' => 'required|array',          
            'nama_profesi.*' => 'required|string|max:255',
            'id_bagians.*' => 'exists:bagians,id',
        ]);

        foreach ($request->nama_profesi as $index => $profesi) {
            Profesi::create([
                'nama_profesi' => $profesi,
                'id_bagians' => $request->id_bagians[$index] ?? null,
            ]);
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
            'id_bagians' => 'required|exists:bagians,id',
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
