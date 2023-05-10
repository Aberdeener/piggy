<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountBalanceResource;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Inertia\Inertia;
use Inertia\Response;
use Cknow\Money\Money;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        return AccountResource::collection(request()->user()->accounts()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request): JsonResource
    {
        $account = new Account($request->validated());
        $account->user()->associate($request->user());
        $account->balances()->create([
            'amount' => Money::USD($request->balance),
        ]);
        $account->save();

        return new AccountResource($account);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account): Response
    {
        $this->authorize('view', $account);

        return Inertia::render('Account', [
            'account' => new AccountResource($account),
            'accountBalanceHistory' => AccountBalanceResource::collection($account->balances),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account): JsonResource
    {
        $this->authorize('update', $account);

        $account->update($request->validated());

        return new AccountResource($account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account): JsonResponse
    {
        $this->authorize('delete', $account);

        $account->delete();

        return response()->json([
            'message' => 'Account deleted successfully',
        ]);
    }
}
