<?php

namespace App\Enums;

enum BalanceAggregation: string {

    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
    case Yearly = 'yearly';

}
