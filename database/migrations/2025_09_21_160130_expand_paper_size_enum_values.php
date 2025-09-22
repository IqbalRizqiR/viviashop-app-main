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
        Schema::table('product_variants', function (Blueprint $table) {
            // Expand the paper_size enum to include more options
            $table->enum('paper_size', ['A4', 'A3', 'A5', 'F4', 'Letter', 'Legal'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // Revert back to original enum values
            $table->enum('paper_size', ['A4', 'A3', 'F4'])->nullable()->change();
        });
    }
};
