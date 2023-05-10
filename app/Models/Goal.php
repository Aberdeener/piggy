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
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'target_amount' => MoneyIntegerCast::class,
        'current_amount' => MoneyIntegerCast::class,
        'target_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

        // off track if auto deposits are not enough to reach target by target date
        $autoDepositSum = $this->autoDeposits->reduce(function (Money $sum, GoalAutoDeposit $autoDeposit) {
            return $autoDeposit->amount->multiply($autoDeposit->determineIterationsUntil($this->target_date));
        }, Money::USD(0));
        if ($this->current_amount->add($autoDepositSum)->lessThan($this->target_amount)) {
            return GoalStatus::OffTrack;
        }

        return GoalStatus::OnTrack;
    }

    public function completionPercentage(): float
    {
        return round($this->current_amount->getAmount() / $this->target_amount->getAmount(), 4) * 100;
    }
}
