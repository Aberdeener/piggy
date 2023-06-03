<?php

use App\Models\Account;
use App\Models\AccountBalance;
use App\Models\Balance;
use App\Models\CreditCard;
use App\Models\CreditCardBalance;
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
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->morphs('balanceable');
            $table->integer('balance');
            $table->timestamps();
        });

        AccountBalance::all()->each(function (AccountBalance $accountBalance) {
            Balance::query()->create([
                'balanceable_id' => $accountBalance->account_id,
                'balanceable_type' => Account::class,
                'balance' => $accountBalance->balance,
                'created_at' => $accountBalance->created_at,
                'updated_at' => $accountBalance->updated_at,
            ]);
        });
        Schema::dropIfExists('account_balances');

        CreditCardBalance::all()->each(function (CreditCardBalance $creditCardBalance) {
            Balance::query()->create([
                'balanceable_id' => $creditCardBalance->credit_card_id,
                'balanceable_type' => CreditCard::class,
                'balance' => $creditCardBalance->balance,
                'created_at' => $creditCardBalance->created_at,
                'updated_at' => $creditCardBalance->updated_at,
            ]);
        });
        Schema::dropIfExists('credit_card_balances');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balances');
    }
};
