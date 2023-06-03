<?php

namespace App\Models;

use Cknow\Money\Casts\MoneyIntegerCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Balance extends Model
{
    protected $casts = [
        'balance' => MoneyIntegerCast::class,
    ];

    protected $guarded = [];

    public function balanceable(): MorphTo
    {
        return $this->morphTo();
    }
}
