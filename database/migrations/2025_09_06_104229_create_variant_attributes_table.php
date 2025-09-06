<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('variant_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variant_id');
            $table->string('attribute_name');
            $table->string('attribute_value');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->index(['variant_id', 'attribute_name']);
            $table->index(['attribute_name', 'attribute_value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_attributes');
    }
};
