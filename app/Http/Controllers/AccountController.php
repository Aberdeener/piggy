<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyAccountRequest;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Resources\AccountBalanceResource;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Inertia\Inertia;
use Inertia\Response;
use Cknow\Money\Money;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Account::class);
    }

    public function store(StoreAccountRequest $request) {
        $account = new Account();
        $account->name = $request->name;
        $account->user_id = request()->user()->id;
        $account->save();

        $account->balances()->create([
            'balance' => Money::USD($request->balance),
        ]);

        $account->user->updateNetWorth();

        return to_route('dashboard');
    }

    public function show(Account $account): Response
    {
        return Inertia::render('Accounts/Show', [
            'account' => new AccountResource($account),
            'accountBalanceHistory' => AccountBalanceResource::collection($account->balances),
        ]);
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {
        $account->update($request->validated());

        return back();
    }

    public function destroy(DestroyAccountRequest $request, Account $account)
    {
        $account->delete();

        $account->user->updateNetWorth();

        return to_route('dashboard');
    }
}
