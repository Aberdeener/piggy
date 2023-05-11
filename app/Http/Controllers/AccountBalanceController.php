<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountBalanceUpdateRequest;
use App\Models\Account;
use App\Models\AccountBalance;
use Cknow\Money\Money;

class AccountBalanceController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(AccountBalanceUpdateRequest $request, Account $account)
    {
        $account->updateBalance(Money::USD($request->balance));

        return back();
    }
}
