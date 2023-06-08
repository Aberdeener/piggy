<?php

namespace App\Models;

use App\Enums\GoalStatus;
use App\Models\Concerns\HasBalance;
use Cknow\Money\Casts\MoneyIntegerCast;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    use HasFactory, SoftDeletes;
    use HasBalance;

    protected $casts = [
        'target_amount' => MoneyIntegerCast::class,
        'target_date' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'target_amount',
        'target_date',
        'account_id',
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
        $this->updateBalance($this->latestBalance()->add($amount));
    }

    public function status(): GoalStatus
    {
        if ($this->latestBalance()->greaterThanOrEqual($this->target_amount)) {
            return GoalStatus::Completed;
        }

        if ($this->projectedTotal()->lessThan($this->target_amount)) {
            return GoalStatus::OffTrack;
        }

        if ($this->target_date->isPast()) {
            return GoalStatus::OffTrack;
        }

        return GoalStatus::OnTrack;
    }

    public function completionPercentage(): string
    {
        return number_format($this->latestBalance()->getAmount() / $this->target_amount->getAmount() * 100, 2);
    }

    public function projectedStatus(): GoalStatus
    {
        if ($this->projectedTotal()->greaterThanOrEqual($this->target_amount)) {
            return GoalStatus::Completed;
        }

        if ($this->projectedTotal()->lessThan($this->target_amount)) {
            return GoalStatus::OffTrack;
        }

        if ($this->target_date->isPast()) {
            return GoalStatus::OffTrack;
        }

        return GoalStatus::OnTrack;
    }

    // TODO: PROJECTED TARGET DATE

    public function projectedTotal(): Money
    {
        $projectedAutoDepositTotal = $this->autoDeposits->filter(function (GoalAutoDeposit $autoDeposit) {
            return $autoDeposit->enabled;
        })->reduce(function (Money $sum, GoalAutoDeposit $autoDeposit) {
            return $sum->add($autoDeposit->amount->multiply($autoDeposit->determineIterationsUntil($this->target_date)));
        }, Money::USD(0));

        return $this->latestBalance()->add($projectedAutoDepositTotal);
    }

    public function projectedCompletionPercentage(): string
    {
        return number_format($this->projectedTotal()->getAmount() / $this->target_amount->getAmount() * 100, 2);
    }
}
