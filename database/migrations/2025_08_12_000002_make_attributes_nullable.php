<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->unsignedBigInteger('attribute_id')->nullable()->change();
        });
        
        Schema::table('products', function (Blueprint $table) {
            $table->text('attributes')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->unsignedBigInteger('attribute_id')->nullable(false)->change();
        });
        
        Schema::table('products', function (Blueprint $table) {
            $table->text('attributes')->nullable(false)->change();
        });
    }
};
