<?php

namespace App\Models;

use App\Enums\GoalAutoDepositFrequency;
use Carbon\Carbon;
use Cknow\Money\Casts\MoneyIntegerCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoalAutoDeposit extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'frequency' => GoalAutoDepositFrequency::class,
        'amount' => MoneyIntegerCast::class,
        'last_deposit_date' => 'datetime',
        'next_deposit_date' => 'datetime',
    ];

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);

        static::created(static function (GoalAutoDeposit $goalAutoDeposit) {
            $goalAutoDeposit->calculateNextDepositDate();
        });
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    public function withdrawAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'withdraw_account_id');
    }

    public function shouldDeposit(): bool
    {
        return $this->next_deposit_date->isToday();
    }

    public function deposit(): void
    {
        $this->goal->incrementCurrentAmount($this->amount);
        $this->calculateNextDepositDate();
    }

    public function calculateNextDepositDate(): void
    {
        if ($this->last_deposit_date === null) {
            $this->last_deposit_date = now();
        }

        $this->next_deposit_date = match ($this->frequency) {
            GoalAutoDepositFrequency::Daily => $this->last_deposit_date->addDay(),
            GoalAutoDepositFrequency::Weekly => $this->last_deposit_date->addWeek(),
            GoalAutoDepositFrequency::Monthly => $this->last_deposit_date->addMonth(),
            GoalAutoDepositFrequency::Yearly => $this->last_deposit_date->addYear(),
        };

        $this->last_deposit_date = now();

        $this->save();
    }

    public function determineIterationsUntil(Carbon $targetDate): int
    {
        $iterations = 0;
        $nextDepositDate = $this->next_deposit_date;

        while ($nextDepositDate->lessThan($targetDate)) {
            $iterations++;
            $nextDepositDate = match ($this->frequency) {
                GoalAutoDepositFrequency::Daily => $nextDepositDate->addDay(),
                GoalAutoDepositFrequency::Weekly => $nextDepositDate->addWeek(),
                GoalAutoDepositFrequency::Monthly => $nextDepositDate->addMonth(),
                GoalAutoDepositFrequency::Yearly => $nextDepositDate->addYear(),
            };
        }

        return $iterations;
    }
}
