<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\SubAttributeOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeRequest;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = Attribute::orderBy('name', 'ASC')->get();

        return view('admin.attributes.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Attribute::types();
        $booleanOptions = Attribute::booleanOptions();
        $validations = Attribute::validations();

        return view('admin.attributes.create', compact('types', 'booleanOptions', 'validations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeRequest $request)
    {
        $attribute = Attribute::create($request->validated());

        return redirect()->route('admin.attributes.edit', $attribute)->with([
            'message' => 'berhasil di buat !',
            'alert-type' => 'success'
        ]);
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
    public function edit(Attribute $attribute)
    {
        $types = Attribute::types();
        $booleanOptions = Attribute::booleanOptions();
        $validations = Attribute::validations();

        return view('admin.attributes.edit', compact('attribute','types','booleanOptions','validations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeRequest $request, Attribute $attribute)
    {
        $attribute->update($request->validated());

        return redirect()->route('admin.attributes.index')->with([
            'message' => 'Berhasil di edit !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        return redirect()->back()->with([
            'message' => 'Berhasil di hapus',
            'alert-type' => 'danger'
        ]);
    }

    /**
     * Show attribute options for specific attribute
     */
    public function showOptions(Attribute $attribute)
    {
        $attributeOptions = $attribute->attribute_options()->get();
        return view('admin.attributes.options.index', compact('attribute', 'attributeOptions'));
    }

    /**
     * Store attribute option
     */
    public function storeOption(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        AttributeOption::create([
            'name' => $request->name,
            'attribute_id' => $attribute->id
        ]);

        return redirect()->back()->with([
            'message' => 'Option berhasil ditambahkan!',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Delete attribute option
     */
    public function destroyOption(AttributeOption $attributeOption)
    {
        $attributeOption->delete();

        return redirect()->back()->with([
            'message' => 'Option berhasil dihapus!',
            'alert-type' => 'danger'
        ]);
    }

    /**
     * Show sub attribute options for specific attribute option
     */
    public function showSubOptions(AttributeOption $attributeOption)
    {
        $subAttributeOptions = $attributeOption->sub_attribute_options()->get();
        return view('admin.attributes.sub-options.index', compact('attributeOption', 'subAttributeOptions'));
    }

    /**
     * Store sub attribute option
     */
    public function storeSubOption(Request $request, AttributeOption $attributeOption)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        SubAttributeOption::create([
            'name' => $request->name,
            'attribute_option_id' => $attributeOption->id
        ]);

        return redirect()->back()->with([
            'message' => 'Sub-option berhasil ditambahkan!',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Delete sub attribute option
     */
    public function destroySubOption(SubAttributeOption $subAttributeOption)
    {
        $subAttributeOption->delete();

        return redirect()->back()->with([
            'message' => 'Sub-option berhasil dihapus!',
            'alert-type' => 'danger'
        ]);
    }
}
