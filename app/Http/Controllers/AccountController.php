<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Resources\AccountBalanceResource;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Inertia\Inertia;
use Inertia\Response;
use Cknow\Money\Money;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Account::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Accounts/Index', [
            'accounts' => AccountResource::collection(request()->user()->accounts),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Accounts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request): Response
    {
        $account = new Account();
        $account->name = $request->name;
        $account->user_id = request()->user()->id;
        $account->save();

        $account->balances()->create([
            'balance' => Money::USD($request->balance),
        ]);

        $account->user->updateNetWorth();

        return $this->index();
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account): Response
    {
        return Inertia::render('Accounts/Show', [
            'account' => new AccountResource($account),
            'accountBalanceHistory' => AccountBalanceResource::collection($account->balances),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account): JsonResource
    {
        $account->update($request->validated());

        return new AccountResource($account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Account $account): Response
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $account->delete();

        $account->user->updateNetWorth();

        return $this->index();
    }
}
