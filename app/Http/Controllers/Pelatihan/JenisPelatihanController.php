<?php

namespace App\Http\Controllers\Pelatihan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelatihan\JenisPelatihan;

class JenisPelatihanController extends Controller
{
    
    public function index()
    {
        $jenisPelatihans = JenisPelatihan::all();
        return view('pelatihan.jenispelatihan', compact('jenisPelatihans'));
    }

    public function create()
    {
        return view('pelatihan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|array',
            'nama_jenis.*' => 'required|string|max:191',
        ]);

        foreach ($request->nama_jenis as $jenis) {
            JenisPelatihan::create(['nama_jenis' => $jenis]);
        }

        return redirect()->route('jenispelatihan.index')->with('success', 'Jenis Pelatihans created successfully.');
    }

    public function update(Request $request, JenisPelatihan $jenis)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:191',
        ]);

        $jenis->update($request->all());

        return redirect()->route('jenispelatihan.index')->with('success', 'Jenis Pelatihan updated successfully.');
    }

    public function destroy(JenisPelatihan $jenis)
    {
        $jenis->delete();

        return redirect()->route('jenispelatihan.index')->with('success', 'Jenis Pelatihan deleted successfully.');
    }
}
