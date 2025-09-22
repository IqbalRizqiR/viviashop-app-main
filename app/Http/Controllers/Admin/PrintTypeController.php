<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrintType;
use Illuminate\Http\Request;

class PrintTypeController extends Controller
{
    public function index()
    {
        $printTypes = PrintType::ordered()->get();
        return view('admin.print-types.index', compact('printTypes'));
    }

    public function create()
    {
        return view('admin.print-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:print_types',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_multiplier' => 'required|numeric|min:0',
            'sort_order' => 'required|integer|min:0'
        ]);

        PrintType::create($request->all());

        return redirect()->route('admin.print-types.index')->with([
            'message' => 'Print type created successfully!',
            'alert-type' => 'success'
        ]);
    }

    public function edit(PrintType $printType)
    {
        return view('admin.print-types.edit', compact('printType'));
    }

    public function update(Request $request, PrintType $printType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:print_types,name,' . $printType->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_multiplier' => 'required|numeric|min:0',
            'sort_order' => 'required|integer|min:0'
        ]);

        $printType->update($request->all());

        return redirect()->route('admin.print-types.index')->with([
            'message' => 'Print type updated successfully!',
            'alert-type' => 'info'
        ]);
    }

    public function destroy(PrintType $printType)
    {
        $printType->delete();

        return redirect()->back()->with([
            'message' => 'Print type deleted successfully!',
            'alert-type' => 'danger'
        ]);
    }

    public function getActivePrintTypes()
    {
        return response()->json([
            'success' => true,
            'data' => PrintType::active()->ordered()->get()
        ]);
    }
}
