<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variant_id');
            $table->enum('movement_type', ['in', 'out']);
            $table->integer('quantity');
            $table->integer('old_stock');
            $table->integer('new_stock');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->index(['variant_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('reason');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
};
