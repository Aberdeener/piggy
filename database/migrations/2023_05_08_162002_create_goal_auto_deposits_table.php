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
        Schema::create('goal_auto_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->constrained();
            $table->foreignId('withdraw_account_id')->constrained('accounts')->cascadeOnDelete();
            $table->integer('amount');
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'yearly']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('next_deposit_date')->nullable();
            $table->date('last_deposit_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_auto_deposits');
    }
};
