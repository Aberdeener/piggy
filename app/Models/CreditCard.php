<?php

namespace App\Models;

use App\Enums\CreditCardUtilization;
use Cknow\Money\Casts\MoneyIntegerCast;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreditCard extends Model
{
    use HasFactory;

    protected $casts = [
        'limit' => MoneyIntegerCast::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function balances(): HasMany
    {
        return $this->hasMany(CreditCardBalance::class);
    }

    public function latestBalance(): Money
    {
        return $this->balances()->latest()->first()->balance;
    }

    public function utilization(): CreditCardUtilization
    {
        if ($this->latestBalance()->greaterThan($this->limit)) {
            return CreditCardUtilization::OverLimit;
        }

        if ($this->latestBalance()->lessThan($this->limit->multiply(0.3))) {
            return CreditCardUtilization::Low;
        }

        if ($this->latestBalance()->lessThan($this->limit->multiply(0.7))) {
            return CreditCardUtilization::Medium;
        }

        return CreditCardUtilization::High;
    }

    public function utilizationPercentage(): float
    {
        return round($this->latestBalance()->getAmount() / $this->limit->getAmount(), 4) * 100;
    }
}
