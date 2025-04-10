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
        Schema::create('rekaman_stoks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamp('waktu')->nullable();
            $table->integer('stok_masuk')->nullable();
            $table->integer('stok_keluar')->nullable();
            $table->integer('id_penjualan')->nullable();
            $table->integer('id_pembelian')->nullable();
            $table->integer('stok_awal')->nullable();
            $table->integer('stok_sisa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekaman_stoks');
    }
};
