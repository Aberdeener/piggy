<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditCardBalanceUpdateRequest;
use App\Models\CreditCard;
use Cknow\Money\Money;

class CreditCardBalanceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(CreditCard::class, 'creditCard');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreditCardBalanceUpdateRequest $request, CreditCard $creditCard)
    {
        $creditCard->updateBalance(Money::USD($request->balance));

        return back();
    }
}
