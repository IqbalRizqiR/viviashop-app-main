<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\AttributeOption;
use App\Models\AttributeVariant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeOptionRequest;

class AttributeOptionController extends Controller
{
    public function index()
    {
        return view('admin.attribute_options.index');
    }

    public function create(Attribute $attribute, AttributeVariant $attribute_variant)
    {
        return view('admin.attribute_options.create', compact('attribute', 'attribute_variant'));
    }

    public function store(AttributeOptionRequest $request, Attribute $attribute, AttributeVariant $attribute_variant)
    {
        $attribute_variant->attribute_options()->create($request->validated());

        return redirect()->route('admin.attributes.edit', $attribute)->with([
            'message' => 'Berhasil di buat !',
            'alert-type' => 'success'
        ]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Attribute $attribute, AttributeVariant $attribute_variant, AttributeOption $attribute_option)
    {
        return view('admin.attribute_options.edit',compact('attribute', 'attribute_variant', 'attribute_option'));
    }

    public function update(AttributeOptionRequest $request, Attribute $attribute, AttributeVariant $attribute_variant, AttributeOption $attribute_option)
    {
        $attribute_option->update($request->validated());

        return redirect()->route('admin.attributes.edit', $attribute)->with([
            'message' => 'Berhasil di edit !',
            'alert-type' => 'info'
        ]);
    }

    public function destroy(Attribute $attribute, AttributeVariant $attribute_variant, AttributeOption $attribute_option)
    {
        $attribute_option->delete();

        return redirect()->back()->with([
            'message' => 'Berhasil di hapus !',
            'alert-type' => 'danger'
        ]);
    }
}
