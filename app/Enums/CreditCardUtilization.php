<?php

namespace App\Enums;

enum CreditCardUtilization: string {

    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case OverLimit = 'over_limit';

}
