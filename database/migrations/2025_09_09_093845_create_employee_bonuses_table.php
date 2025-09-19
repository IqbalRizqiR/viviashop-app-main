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
        Schema::create('employee_bonuses', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->decimal('bonus_amount', 15, 2);
            $table->date('period_start');
            $table->date('period_end');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('given_by');
            $table->timestamp('given_at')->useCurrent();
            $table->timestamps();
            
            $table->foreign('given_by')->references('id')->on('users');
            $table->index(['employee_name', 'period_start', 'period_end']);
            $table->index(['given_by', 'given_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_bonuses');
    }
};
