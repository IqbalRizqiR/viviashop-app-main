<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_token', 32)->unique();
            $table->string('barcode_token', 32)->unique();
            $table->boolean('is_active')->default(true);
            $table->enum('current_step', ['upload', 'select', 'payment', 'print', 'complete'])->default('upload');
            $table->timestamp('started_at');
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index(['is_active', 'expires_at']);
            $table->index('session_token');
            $table->index('barcode_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_sessions');
    }
};
