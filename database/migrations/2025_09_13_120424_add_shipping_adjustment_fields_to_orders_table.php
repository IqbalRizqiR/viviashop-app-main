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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('original_shipping_cost', 16, 2)->nullable()->after('shipping_cost');
            $table->string('original_shipping_courier')->nullable()->after('shipping_courier');
            $table->string('original_shipping_service_name')->nullable()->after('shipping_service_name');
            $table->boolean('shipping_cost_adjusted')->default(false)->after('original_shipping_service_name');
            $table->text('shipping_adjustment_note')->nullable()->after('shipping_cost_adjusted');
            $table->timestamp('shipping_adjusted_at')->nullable()->after('shipping_adjustment_note');
            $table->unsignedBigInteger('shipping_adjusted_by')->nullable()->after('shipping_adjusted_at');
            
            $table->foreign('shipping_adjusted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_adjusted_by']);
            $table->dropColumn([
                'original_shipping_cost',
                'original_shipping_courier', 
                'original_shipping_service_name',
                'shipping_cost_adjusted',
                'shipping_adjustment_note',
                'shipping_adjusted_at',
                'shipping_adjusted_by'
            ]);
        });
    }
};
