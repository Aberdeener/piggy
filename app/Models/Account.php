<?php

namespace App\Models;

use App\Concerns\HasBalance;
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
    use HasBalance;

    protected $fillable = [
        'name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function autoDeposits(): BelongsToMany
    {
        return $this->belongsToMany(GoalAutoDeposit::class, 'goal_auto_deposits');
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    public function updateBalance(Money $balance): void
    {
        if ($this->latestBalance()->equals($balance)) {
            return;
        }

        $difference = $balance->subtract($this->latestBalance());

        $this->balances()->create([
            'balance' => $balance,
        ]);

        $this->goals->each(function (Goal $goal) use ($difference) {
            $goal->incrementCurrentAmount($difference);
        });

        $this->user->updateNetWorth();
    }
}
