<?php

namespace App\Models;

use Cknow\Money\Casts\MoneyIntegerCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountBalance extends Model
{
    use HasFactory;

    protected $casts = [
        'balance' => MoneyIntegerCast::class,
    ];

    protected $fillable = [
        'balance',
    ];
}
