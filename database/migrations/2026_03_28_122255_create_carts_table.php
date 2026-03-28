<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique('user_id'); // One cart per user
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->integer('qty')->default(1);
            $table->decimal('price', 15, 2);
            $table->json('options')->nullable(); // variant attributes, type, weight, etc.
            $table->timestamps();

            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');
            $table->unique(['cart_id', 'product_id', 'variant_id']); // No duplicate items
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
