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
        Schema::table('employee_bonuses', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_bonuses', 'description')) {
                $table->text('description')->nullable()->after('bonus_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_bonuses', function (Blueprint $table) {
            if (Schema::hasColumn('employee_bonuses', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
