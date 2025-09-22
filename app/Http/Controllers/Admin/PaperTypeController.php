<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaperType;
use Illuminate\Http\Request;

class PaperTypeController extends Controller
{
    public function index()
    {
        $paperTypes = PaperType::ordered()->get();
        return view('admin.paper-types.index', compact('paperTypes'));
    }

    public function create()
    {
        return view('admin.paper-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:paper_types',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_multiplier' => 'required|numeric|min:0',
            'sort_order' => 'required|integer|min:0'
        ]);

        PaperType::create($request->all());

        return redirect()->route('admin.paper-types.index')->with([
            'message' => 'Paper type created successfully!',
            'alert-type' => 'success'
        ]);
    }

    public function edit(PaperType $paperType)
    {
        return view('admin.paper-types.edit', compact('paperType'));
    }

    public function update(Request $request, PaperType $paperType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:paper_types,name,' . $paperType->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_multiplier' => 'required|numeric|min:0',
            'sort_order' => 'required|integer|min:0'
        ]);

        $paperType->update($request->all());

        return redirect()->route('admin.paper-types.index')->with([
            'message' => 'Paper type updated successfully!',
            'alert-type' => 'info'
        ]);
    }

    public function destroy(PaperType $paperType)
    {
        $paperType->delete();

        return redirect()->back()->with([
            'message' => 'Paper type deleted successfully!',
            'alert-type' => 'danger'
        ]);
    }

    public function getActivePaperTypes()
    {
        return response()->json([
            'success' => true,
            'data' => PaperType::active()->ordered()->get()
        ]);
    }
}
