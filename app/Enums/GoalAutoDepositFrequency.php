<?php

namespace App\Enums;

enum GoalAutoDepositFrequency: string {

    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
    case Yearly = 'yearly';

}
