<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\AttributeVariant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeVariantRequest;

class AttributeVariantController extends Controller
{
    public function index()
    {
        return view('admin.attribute_variants.index');
    }

    public function create(Attribute $attribute)
    {
        return view('admin.attribute_variants.create', compact('attribute'));
    }

    public function store(AttributeVariantRequest $request, Attribute $attribute)
    {
        $attribute->attribute_variants()->create($request->validated());

        return redirect()->route('admin.attributes.edit', $attribute)->with([
            'message' => 'Berhasil di buat !',
            'alert-type' => 'success'
        ]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Attribute $attribute, AttributeVariant $attribute_variant)
    {
        return view('admin.attribute_variants.edit',compact('attribute', 'attribute_variant'));
    }

    public function update(AttributeVariantRequest $request, Attribute $attribute, AttributeVariant $attribute_variant)
    {
        $attribute_variant->update($request->validated());

        return redirect()->route('admin.attributes.edit', $attribute)->with([
            'message' => 'Berhasil di edit !',
            'alert-type' => 'info'
        ]);
    }

    public function destroy(Attribute $attribute, AttributeVariant $attribute_variant)
    {
        $attribute_variant->delete();

        return redirect()->back()->with([
            'message' => 'Berhasil di hapus !',
            'alert-type' => 'danger'
        ]);
    }
}
