<?php

namespace App\Models;

use App\Enums\CreditCardUtilization;
use App\Models\Concerns\HasBalance;
use Cknow\Money\Casts\MoneyIntegerCast;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditCard extends Model
{
    use HasFactory;
    use HasBalance;

    protected $casts = [
        'limit' => MoneyIntegerCast::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
        return number_format($this->latestBalance()->getAmount() / $this->limit->getAmount() * 100, 2);
    }

    public function onBalanceUpdated(): void
    {
        $this->user->updateNetWorth();
    }
}
