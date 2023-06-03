<?php

namespace App\Concerns;

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
        return $this->balances()->latest()->first()->balance;
    }
}
