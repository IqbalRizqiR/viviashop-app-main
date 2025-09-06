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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable()->after('user_id');
            $table->decimal('base_price', 15, 2)->nullable()->after('price');
            $table->integer('total_stock')->default(0)->after('base_price');
            $table->integer('sold_count')->default(0)->after('total_stock');
            $table->decimal('rating', 3, 2)->default(0)->after('sold_count');
            $table->boolean('is_featured')->default(false)->after('rating');
            
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->index(['brand_id', 'status']);
            $table->index(['is_featured', 'status']);
            $table->index(['sold_count', 'rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropIndex(['brand_id', 'status']);
            $table->dropIndex(['is_featured', 'status']);
            $table->dropIndex(['sold_count', 'rating']);
            $table->dropColumn(['brand_id', 'base_price', 'total_stock', 'sold_count', 'rating', 'is_featured']);
        });
    }
};
