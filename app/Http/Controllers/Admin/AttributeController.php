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

    /**
     * HTTP Migration for 3-level attributes
     */
    public function migrate(Request $request)
    {
        // Security check
        if (!$request->has('token') || $request->token !== 'SECRET_TOKEN_123') {
            abort(403, 'Access denied. Invalid token.');
        }

        try {
            $output = [];
            $output[] = '<h1>ViVia Shop Migration Script</h1>';
            $output[] = '<p>Starting migration process...</p>';
            
            // Check if sub_attribute_options table exists
            if (!\Schema::hasTable('sub_attribute_options')) {
                $output[] = '<p>Creating sub_attribute_options table...</p>';
                
                \Schema::create('sub_attribute_options', function ($table) {
                    $table->id();
                    $table->string('name');
                    $table->unsignedBigInteger('attribute_option_id');
                    $table->foreign('attribute_option_id')->references('id')->on('attribute_options')->onDelete('cascade');
                    $table->timestamps();
                });
                
                $output[] = '<p style="color: green;">✓ Table sub_attribute_options created successfully!</p>';
            } else {
                $output[] = '<p style="color: orange;">! Table sub_attribute_options already exists, skipping...</p>';
            }
            
            // Insert sample data
            $output[] = '<p>Inserting sample data...</p>';
            
            // Level 1: Attribute (Art Paper)
            $attribute = \DB::table('attributes')->where('code', 'APP')->first();
            if (!$attribute) {
                $attributeId = \DB::table('attributes')->insertGetId([
                    'code' => 'APP',
                    'name' => 'Art Paper',
                    'type' => 'select',
                    'validation' => null,
                    'is_required' => false,
                    'is_unique' => false,
                    'is_filterable' => true,
                    'is_configurable' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $output[] = '<p style="color: green;">✓ Created attribute: Art Paper (APP)</p>';
            } else {
                $attributeId = $attribute->id;
                $output[] = '<p style="color: orange;">! Attribute Art Paper already exists</p>';
            }
            
            // Level 2: Attribute Options (Gramatur)
            $gramaturOptions = ['100gr', '120gr', '150gr', '200gr', '230gr', '260gr'];
            
            foreach ($gramaturOptions as $gramatur) {
                $existingOption = \DB::table('attribute_options')
                    ->where('attribute_id', $attributeId)
                    ->where('name', $gramatur)
                    ->first();
                    
                if (!$existingOption) {
                    $optionId = \DB::table('attribute_options')->insertGetId([
                        'name' => $gramatur,
                        'attribute_id' => $attributeId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $output[] = '<p style="color: green;">✓ Created option: ' . $gramatur . '</p>';
                    
                    // Level 3: Sub Attribute Options (Tipe Cetak)
                    $subOptions = ['Vinyl', 'Digital Print', 'Offset Print', 'UV Print'];
                    
                    foreach ($subOptions as $subOption) {
                        \DB::table('sub_attribute_options')->insert([
                            'name' => $subOption,
                            'attribute_option_id' => $optionId,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        $output[] = '<p style="color: blue;">  ✓ Created sub-option: ' . $subOption . ' for ' . $gramatur . '</p>';
                    }
                } else {
                    $output[] = '<p style="color: orange;">! Option ' . $gramatur . ' already exists</p>';
                }
            }
            
            $output[] = '<h2 style="color: green;">Migration completed successfully!</h2>';
            $output[] = '<p><strong>Struktur yang telah dibuat:</strong></p>';
            $output[] = '<ul>';
            $output[] = '<li><strong>Level 1:</strong> Art Paper (APP) - Atribut utama</li>';
            $output[] = '<li><strong>Level 2:</strong> 100gr, 120gr, 150gr, 200gr, 230gr, 260gr - Varian gramatur</li>';
            $output[] = '<li><strong>Level 3:</strong> Vinyl, Digital Print, Offset Print, UV Print - Tipe cetak untuk setiap gramatur</li>';
            $output[] = '</ul>';
            
            $output[] = '<p><a href="/admin/attributes" style="background: #007cba; color: white; padding: 10px; text-decoration: none; border-radius: 5px;">Go to Attributes Management</a></p>';
            
            return response(implode('', $output));
            
        } catch (\Exception $e) {
            $output = [];
            $output[] = '<h2 style="color: red;">Migration failed!</h2>';
            $output[] = '<p style="color: red;">Error: ' . $e->getMessage() . '</p>';
            $output[] = '<p>Please check your database connection and try again.</p>';
            
            return response(implode('', $output), 500);
        }
    }
}
