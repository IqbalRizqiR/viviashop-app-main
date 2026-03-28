<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('print_files', function (Blueprint $table) {
            $table->unsignedBigInteger('print_session_id')->nullable()->after('id');
            $table->unsignedBigInteger('print_order_id')->nullable()->change();
            
            $table->foreign('print_session_id')->references('id')->on('print_sessions')->onDelete('cascade');
            $table->index(['print_session_id', 'is_processed']);
        });
    }

    public function down(): void
    {
        Schema::table('print_files', function (Blueprint $table) {
            $table->dropForeign(['print_session_id']);
            $table->dropIndex(['print_session_id', 'is_processed']);
            $table->dropColumn('print_session_id');
            $table->unsignedBigInteger('print_order_id')->nullable(false)->change();
        });
    }
};
