<?php

namespace App\Models;

use Cknow\Money\Casts\MoneyIntegerCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCardBalance extends Model
{
    use HasFactory;

    protected $casts = [
        'balance' => MoneyIntegerCast::class,
    ];
}