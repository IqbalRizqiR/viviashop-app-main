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
            $table->string('paper_size')->nullable()->change();
            $table->string('print_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->enum('paper_size', ['A4', 'A3', 'A5', 'F4', 'Letter', 'Legal'])->nullable()->change();
            $table->enum('print_type', ['bw', 'color'])->nullable()->change();
        });
    }
};
