<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
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
        return $this->hasMany(Account::class)
            ->orderBy('created_at', 'desc');
    }

    public function creditCards(): HasMany
    {
        return $this->hasMany(CreditCard::class)
            ->orderBy('created_at', 'desc');
    }

    // TODO: should goals just have a user_id column?
    public function goals(): HasManyThrough
    {
        return $this->hasManyThrough(Goal::class, Account::class)
            ->orderBy('created_at', 'desc');
    }

    public function netWorths(): HasMany
    {
        return $this->hasMany(UserNetWorth::class);
    }

    public function latestNetWorth(): Money
    {
        return $this->netWorths()->latest()->first()?->amount ?? Money::USD(0);
    }

    public function updateNetWorth(): void
    {
        $latestStoredNetWorth = $this->latestNetWorth()->getAmount();
        $currentNetWorth = $this->currentNetWorth()->getAmount();

        if ($latestStoredNetWorth === $currentNetWorth) {
            return;
        }

        $this->netWorths()->create([
            'amount' => Money::USD($currentNetWorth),
        ]);
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
}
