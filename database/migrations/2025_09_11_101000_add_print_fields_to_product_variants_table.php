<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->enum('print_type', ['bw', 'color'])->nullable()->after('barcode');
            $table->enum('paper_size', ['A4', 'A3', 'F4'])->nullable()->after('print_type');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['print_type', 'paper_size']);
        });
    }
};
