<?php

namespace App\Models;

use App\Enums\GoalAutoDepositFrequency;
use Carbon\Carbon;
use Cknow\Money\Casts\MoneyIntegerCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class GoalAutoDeposit extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'frequency' => GoalAutoDepositFrequency::class,
        'amount' => MoneyIntegerCast::class,
        'start_date' => 'datetime',
        'last_deposit_date' => 'datetime',
        'enabled' => 'boolean',
    ];

    protected $fillable = [
        'frequency',
        'amount',
        'withdraw_account_id',
        'start_date',
        'enabled',
    ];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Goal::class);
    }

    public function withdrawAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'withdraw_account_id');
    }

    public function shouldDeposit(): bool
    {
        return ($this->start_date->isToday() || $this->start_date->isPast()) && $this->nextDepositDate()->isToday();
    }

    public function deposit(): void
    {
        $this->goal->incrementCurrentAmount($this->amount);

        $this->last_deposit_date = now();
        $this->save();
    }

    public function nextDepositDate(): Carbon
    {
        if ($this->last_deposit_date === null) {
            $this->last_deposit_date = $this->start_date;
        }

        return $this->carbonOperation(
            $this->frequency,
            $this->last_deposit_date
        );
    }

    public function determineIterationsUntil(Carbon $targetDate): int
    {
        $iterations = 0;
        $nextDepositDate = $this->nextDepositDate()->startOfDay();

        while ($nextDepositDate->lessThan($targetDate) && $nextDepositDate->greaterThan($this->start_date)) {
            $iterations++;
            $nextDepositDate = $this->carbonOperation(
                $this->frequency,
                $nextDepositDate
            );
        }

        return $iterations;
    }

    private function carbonOperation(GoalAutoDepositFrequency $frequency, Carbon $date): Carbon
    {
        return match ($frequency) {
            GoalAutoDepositFrequency::Daily => $date->addDay(),
            GoalAutoDepositFrequency::Weekly => $date->addWeek(),
            GoalAutoDepositFrequency::Monthly => $date->addMonth(),
            GoalAutoDepositFrequency::Yearly => $date->addYear(),
        };
    }
}
