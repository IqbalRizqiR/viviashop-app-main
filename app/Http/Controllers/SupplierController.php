<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $create = Supplier::create($data);

        if ($create) {
            Alert::success('Supplier berhasil ditambahkan!', 'Data supplier berhasil ditambahkan');
            return redirect()->route('admin.supplier.index');
        } else {
            Alert::success('Supplier gagal ditambahkan!', 'Data supplier gagal ditambahkan');
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
    public function edit($id)
    {
        $supplier = Supplier::where('id', $id)->first();
        // dd($supplier);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function data()
    {
        $supplier = Supplier::orderBy('id_supplier', 'desc')->get();

        return datatables()
            ->of($supplier)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('admin.supplier.update', $supplier->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('admin.supplier.destroy', $supplier->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::get('id', $id)->first();
        $update = $supplier->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon
        ]);

        if ($update) {
            Alert::success('Supplier berhasil di update!', 'Data Supplier berhasil di update');
            return redirect()->route('admin.supplier.index');
        } else {
            Alert::success('Supplier gagal di update!', 'Data supplier gagal di update');
            return redirect()->route('admin.supplier.index');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::get('id', $id)->first();
        $delete = $supplier->delete();

        if ($delete) {
            Alert::success('Supplier berhasil di delete!', 'Data Supplier berhasil di delete');
            return redirect()->route('admin.supplier.index');
        } else {
            Alert::success('Supplier gagal di delete!', 'Data supplier gagal di delete');
            return redirect()->route('admin.supplier.index');
        }
    }
}
