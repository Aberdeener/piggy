<?php

namespace App\Models;

use App\Enums\GoalStatus;
use Cknow\Money\Casts\MoneyIntegerCast;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'target_amount' => MoneyIntegerCast::class,
        'current_amount' => MoneyIntegerCast::class,
        'target_date' => 'datetime',
    ];

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Account::class, 'id', 'id', 'account_id', 'user_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function autoDeposits(): HasMany
    {
        return $this->hasMany(GoalAutoDeposit::class);
    }

    public function incrementCurrentAmount(Money $amount): void
    {
        $this->current_amount = $this->current_amount->add($amount);
        $this->save();
    }

    public function status(): GoalStatus
    {
        if ($this->current_amount->greaterThanOrEqual($this->target_amount)) {
            return GoalStatus::Completed;
        }

        if ($this->target_date->lessThan(now())) {
            return GoalStatus::OffTrack;
        }

        if ($this->projectedTotal()->lessThan($this->target_amount)) {
            return GoalStatus::OffTrack;
        }

        return GoalStatus::OnTrack;
    }

    public function completionPercentage(): string
    {
        return number_format($this->current_amount->getAmount() / $this->target_amount->getAmount() * 100, 2);
    }

    public function projectedStatus(): GoalStatus
    {
        if ($this->projectedTotal()->greaterThanOrEqual($this->target_amount)) {
            return GoalStatus::Completed;
        }

        if ($this->target_date->lessThan(now())) {
            return GoalStatus::OffTrack;
        }

        if ($this->projectedTotal()->lessThan($this->target_amount)) {
            return GoalStatus::OffTrack;
        }

        return GoalStatus::OnTrack;
    }

    public function projectedTotal(): Money
    {
        $projectedAutoDepositTotal = $this->autoDeposits->filter(function (GoalAutoDeposit $autoDeposit) {
            return $autoDeposit->enabled;
        })->reduce(function (Money $sum, GoalAutoDeposit $autoDeposit) {
            return $sum->add($autoDeposit->amount->multiply($autoDeposit->determineIterationsUntil($this->target_date)));
        }, Money::USD(0));

        return $this->current_amount->add($projectedAutoDepositTotal);
    }

    public function projectedCompletionPercentage(): string
    {
        return number_format($this->projectedTotal()->getAmount() / $this->target_amount->getAmount() * 100, 2);
    }
}
