<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebSettingController extends Controller
{
    public function index(){
        $settings = WebSetting::first();
        return view('setting.websetting', compact('settings'));
    }


    public function update(Request $request, WebSetting $websetting)
    {
        // dd($websetting);
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5120',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'facebook' => 'nullable',
            'instagram' => 'nullable',
            'twitter' => 'nullable',
            'youtube' => 'nullable',
            'website' => 'nullable',
            'coursellink1' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5120',
            'coursellink2' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5120',
            'coursellink3' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        // dd($request->all());

        // Initialize $data as an empty array
        // $data = [];

        // Use the provided WebSetting instance directly
        $websetting = $websetting;

        // Menyimpan logo baru jika diupload
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($websetting->logo && file_exists(public_path($websetting->logo))) {
                unlink(public_path($websetting->logo));
            }

            // Menyimpan gambar ke folder public/images/karyawan
            $logoPath = $request->file('logo')->move(public_path('images/settings'), $request->file('logo')->getClientOriginalName());
            $data['logo'] = 'images/settings/' . $request->file('logo')->getClientOriginalName();
        }

        // Proses upload carousel images
        if ($request->hasFile('coursellink1')) {
            // Hapus gambar lama jika ada
            if ($websetting->coursellink1 && file_exists(public_path($websetting->coursellink1))) {
                unlink(public_path($websetting->coursellink1));
            }

            // Menyimpan gambar ke folder public/images/karyawan
            $coursellink1Path = $request->file('coursellink1')->move(public_path('images/settings'), $request->file('coursellink1')->getClientOriginalName());
            $data['coursellink1'] = 'images/settings/' . $request->file('coursellink1')->getClientOriginalName();
        }

        if ($request->hasFile('coursellink2')) {
            // Hapus gambar lama jika ada
            if ($websetting->coursellink2 && file_exists(public_path($websetting->coursellink2))) {
                unlink(public_path($websetting->coursellink2)); // Hapus gambar lama jika ada   
            }
            // Menyimpan gambar ke folder public/images/karyawan
            $coursellink2Path = $request->file('coursellink2')->move(public_path('images/settings'), $request->file('coursellink2')->getClientOriginalName());
            $data['coursellink2'] = 'images/settings/' . $request->file('coursellink2')->getClientOriginalName();
        }

        if ($request->hasFile('coursellink3')) {
            // Hapus gambar lama jika ada
            if ($websetting->coursellink3 && file_exists(public_path($websetting->coursellink3))) {
                unlink(public_path($websetting->coursellink3)); // Hapus gambar lama jika ada   
            }
            // Menyimpan gambar ke folder public/images/karyawan
            $coursellink3Path = $request->file('coursellink3')->move(public_path('images/settings'), $request->file('coursellink3')->getClientOriginalName());
            $data['coursellink3'] = 'images/settings/' . $request->file('coursellink3')->getClientOriginalName();
        }   

        $websetting->update($data);

        return redirect()->route('websetting.index')->with('success', 'Data berhasil diperbarui.');
    }

}
