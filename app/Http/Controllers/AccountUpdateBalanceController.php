<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccountBalanceRequest;
use App\Models\Account;
use Cknow\Money\Money;

class AccountUpdateBalanceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Account::class);
    }

    public function __invoke(UpdateAccountBalanceRequest $request, Account $account)
    {
        $account->updateBalance(Money::USD($request->balance));

        return back();
    }
}
