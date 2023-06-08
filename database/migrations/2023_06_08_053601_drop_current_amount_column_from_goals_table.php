<?php

use App\Models\Goal;
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
        Goal::each(static function (Goal $goal) {
            $goal->updateBalance(\Cknow\Money\Money::USD($goal->current_amount));
        });

        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn('current_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            //
        });
    }
};
