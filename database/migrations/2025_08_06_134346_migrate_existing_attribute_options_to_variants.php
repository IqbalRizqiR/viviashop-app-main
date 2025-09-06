<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('attribute_variants')) {
            Schema::create('attribute_variants', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('attribute_id');
                $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
                $table->timestamps();
            });

            $attributes = DB::table('attributes')->get();
            foreach ($attributes as $attribute) {
                DB::table('attribute_variants')->insert([
                    'name' => $attribute->name . ' Variant',
                    'attribute_id' => $attribute->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $firstVariant = DB::table('attribute_variants')->first();
            if ($firstVariant) {
                DB::table('attribute_options')->update(['attribute_variant_id' => $firstVariant->id]);
            }
        }

        if (!Schema::hasColumn('attribute_options', 'attribute_variant_id')) {
            Schema::table('attribute_options', function (Blueprint $table) {
                $table->unsignedBigInteger('attribute_variant_id')->nullable();
            });
        }

        $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_NAME = 'attribute_options' AND CONSTRAINT_TYPE = 'FOREIGN KEY' AND CONSTRAINT_NAME = 'attribute_options_attribute_variant_id_foreign'");
        if (empty($foreignKeys)) {
            Schema::table('attribute_options', function (Blueprint $table) {
                $table->foreign('attribute_variant_id')->references('id')->on('attribute_variants')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('attribute_options', function (Blueprint $table) {
            $table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
        });

        $attributeVariants = DB::table('attribute_variants')->get();
        foreach ($attributeVariants as $variant) {
            DB::table('attribute_options')
                ->where('attribute_variant_id', $variant->id)
                ->update(['attribute_id' => $variant->attribute_id]);
        }

        Schema::table('attribute_options', function (Blueprint $table) {
            $table->dropColumn('attribute_variant_id');
        });

        Schema::dropIfExists('attribute_variants');
    }
};
