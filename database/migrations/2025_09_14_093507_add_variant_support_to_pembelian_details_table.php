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
        Schema::table('pembelian_details', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id')->nullable()->after('id_produk');
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->index(['id_pembelian', 'id_produk']);
            $table->index(['id_pembelian', 'variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian_details', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropIndex(['id_pembelian', 'id_produk']);
            $table->dropIndex(['id_pembelian', 'variant_id']);
            $table->dropColumn('variant_id');
        });
    }
};
