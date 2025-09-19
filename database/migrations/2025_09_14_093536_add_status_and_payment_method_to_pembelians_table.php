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
        Schema::table('pembelians', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->after('bayar');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'credit'])->default('cash')->after('status');
            $table->text('notes')->nullable()->after('payment_method');
            $table->index('status');
            $table->index(['id_supplier', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropIndex(['id_supplier', 'status']);
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'payment_method', 'notes']);
        });
    }
};
