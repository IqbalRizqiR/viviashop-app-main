<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengeluaran = Pengeluaran::all();
        return view('admin.pengeluaran.index', compact('pengeluaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pengeluaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $create = Pengeluaran::create($data);

        if ($create) {
            Alert::success('Data berhasil', 'Data berhasil di tambahkan!');
            return redirect()->route('admin.pengeluaran.index');
        } else {
            Alert::error('Data gagal', 'Data gagal di tambahkan!');
            return redirect()->back();
        }
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
        $pengeluaran = Pengeluaran::where('id', $id)->first();

        return view('admin.pengeluaran.edit', compact('pengeluaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pengeluaran = Pengeluaran::where('id', $id)->first();

        $update = $pengeluaran->update([
            'nominal' => $request->nominal,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($update) {
            Alert::success('Data berhasil', 'Data berhasil di update!');
            return redirect()->route('admin.pengeluaran.index');
        } else {
            Alert::error('Data gagal', 'Data gagal di update!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengeluaran = Pengeluaran::where('id', $id)->first();

        $delete = $pengeluaran->delete();

        if ($delete) {
            Alert::success('Data berhasil', 'Data berhasil di delete!');
            return redirect()->route('admin.pengeluaran.index');
        } else {
            Alert::error('Data gagal', 'Data gagal di delete!');
            return redirect()->back();
        }
    }
}
