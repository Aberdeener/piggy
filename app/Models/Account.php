<?php

namespace App\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function balances(): HasMany
    {
        return $this->hasMany(AccountBalance::class);
    }

    public function latestBalance(): Money
    {
        return $this->balances()->latest()->first()->balance;
    }

    public function autoDeposits(): BelongsToMany
    {
        return $this->belongsToMany(GoalAutoDeposit::class, 'goal_auto_deposits');
    }

    public function updateBalance(int $balance): void
    {
        $this->balances()->create([
            'balance' => Money::USD($balance),
        ]);

        $this->user->updateNetWorth();
    }
}
