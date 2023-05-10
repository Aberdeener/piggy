<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function creditCards(): HasMany
    {
        return $this->hasMany(CreditCard::class);
    }

    public function goals(): HasManyThrough
    {
        return $this->hasManyThrough(Goal::class, Account::class);
    }

    public function netWorths(): HasMany
    {
        return $this->hasMany(NetWorth::class);
    }

    public function updateNetWorth(): void
    {
        $latestStoredNetWorth = $this->netWorths()->latest()->first()?->amount->getAmount();
        $currentNetWorth = $this->currentNetWorth()->getAmount();

        if ($latestStoredNetWorth === $currentNetWorth) {
            return;
        }

        $netWorth = new NetWorth();
        $netWorth->user_id = $this->id;
        $netWorth->amount = Money::USD($currentNetWorth);
        $netWorth->save();
    }

    public function currentNetWorth(): Money
    {
        $accountsBalance = $this->accounts->reduce(function (Money $balance, Account $account) {
            return $balance->add($account->latestBalance());
        }, Money::USD(0));

        return $accountsBalance->subtract($this->creditCards->reduce(function (Money $owing, CreditCard $creditCard) {
            return $owing->add($creditCard->latestBalance());
        }, Money::USD(0)));
    }

    public function netWorth(): array
    {
        return [
            'current' => $this->currentNetWorth(),
            'history' => [
                ...$this->netWorths()->chunkMap(fn (NetWorth $netWorth) => ['date' => $netWorth->created_at, 'amount' => $netWorth->amount]),
            ],
        ];
    }
}
