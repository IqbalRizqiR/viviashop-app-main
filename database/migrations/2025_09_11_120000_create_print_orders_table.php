<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->string('customer_phone', 20);
            $table->string('customer_name');
            $table->json('file_data');
            $table->unsignedBigInteger('paper_product_id');
            $table->unsignedBigInteger('paper_variant_id');
            $table->enum('print_type', ['bw', 'color']);
            $table->integer('quantity');
            $table->integer('total_pages');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending_upload', 'uploaded', 'payment_pending', 'payment_confirmed', 'ready_to_print', 'printing', 'printed', 'completed', 'cancelled'])->default('pending_upload');
            $table->enum('payment_method', ['manual', 'automatic', 'toko']);
            $table->enum('payment_status', ['unpaid', 'waiting', 'paid'])->default('unpaid');
            $table->string('payment_proof')->nullable();
            $table->string('payment_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->unsignedBigInteger('session_id');
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('paper_product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('paper_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('print_sessions')->onDelete('cascade');
            
            $table->index(['status', 'payment_status']);
            $table->index(['session_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_orders');
    }
};
