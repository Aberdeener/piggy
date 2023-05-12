<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditCardBalanceUpdateRequest;
use App\Models\CreditCard;
use Cknow\Money\Money;

class CreditCardUpdateBalanceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(CreditCard::class);
    }

    public function __invoke(CreditCardBalanceUpdateRequest $request, CreditCard $creditCard)
    {
        $creditCard->updateBalance(Money::USD($request->balance));

        return back();
    }
}
