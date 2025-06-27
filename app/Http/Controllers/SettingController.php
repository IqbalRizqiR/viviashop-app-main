<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = Setting::where('id', 1)->first();
        return view('admin.settings.index', compact('setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $setting = Setting::where('id', 1)->first();
        $update = $setting->update([
            'nama_toko' => $request->nama_toko,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'path_logo' => $request->path_logo,
        ]);

        if ($update) {
            Alert::success('setting berhasil di update!', 'Data setting berhasil di update');
            return redirect()->route('setting.index');
        } else {
            Alert::success('setting gagal di update!', 'Data setting gagal di update');
            return redirect()->route('setting.index');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
