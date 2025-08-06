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
        Schema::table('attribute_options', function (Blueprint $table) {
            $table->dropForeign(['attribute_id']);
            $table->dropColumn('attribute_id');
            $table->unsignedBigInteger('attribute_variant_id');
            $table->foreign('attribute_variant_id')->references('id')->on('attribute_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribute_options', function (Blueprint $table) {
            $table->dropForeign(['attribute_variant_id']);
            $table->dropColumn('attribute_variant_id');
            $table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
        });
    }
};
