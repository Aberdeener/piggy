<?php

namespace App\Models;

use Cknow\Money\Casts\MoneyIntegerCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetWorth extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => MoneyIntegerCast::class,
    ];
}
