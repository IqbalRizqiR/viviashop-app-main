<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->unsignedBigInteger('attribute_variant_id')->nullable()->after('attribute_id');
            $table->unsignedBigInteger('attribute_option_id')->nullable()->after('attribute_variant_id');
            
            $table->foreign('attribute_variant_id')->references('id')->on('attribute_variants')->onDelete('cascade');
            $table->foreign('attribute_option_id')->references('id')->on('attribute_options')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->dropForeign(['attribute_variant_id']);
            $table->dropForeign(['attribute_option_id']);
            $table->dropColumn(['attribute_variant_id', 'attribute_option_id']);
        });
    }
};
