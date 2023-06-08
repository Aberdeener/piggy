<?php

namespace App\Models\Concerns;

use App\Enums\BalanceAggregation;
use App\Models\Balance;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasBalance
{

    public function balances(): MorphMany
    {
        return $this->morphMany(Balance::class, 'balanceable');
    }

    public function latestBalance(): Money
    {
        return $this->balances()->latest()->first()->balance ?? Money::USD(0);
    }

    public function updateBalance(Money $newBalance): void
    {
        if ($this->latestBalance()->equals($newBalance)) {
            return;
        }

        $difference = $newBalance->subtract($this->latestBalance());

        $this->balances()->create([
            'balance' => $newBalance,
        ]);

        if (method_exists($this, 'onBalanceUpdated')) {
            $this->onBalanceUpdated($difference);
        }
    }

    public function showAggregatedBalances(BalanceAggregation $aggregation): array
    {
        // daily = show average balance for each of the last 24 hours
        // weekly = show average balance for each of the last 7 days
        // monthly = show average balance for each of the last 30 days
        // yearly = show average balance for each of the last 365 days

        return [];
    }
}
