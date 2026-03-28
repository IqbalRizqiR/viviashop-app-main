<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('print_order_id');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type', 10);
            $table->bigInteger('file_size');
            $table->integer('pages_count');
            $table->boolean('is_processed')->default(false);
            $table->timestamps();

            $table->foreign('print_order_id')->references('id')->on('print_orders')->onDelete('cascade');
            $table->index(['print_order_id', 'is_processed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_files');
    }
};
