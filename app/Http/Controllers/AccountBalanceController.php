<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountBalanceRequest;
use App\Http\Requests\UpdateAccountBalanceRequest;
use App\Models\Account;
use App\Models\AccountBalance;

class AccountBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountBalanceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountBalance $accountBalance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountBalance $accountBalance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountBalanceRequest $request, Account $account)
    {
        $account->updateBalance($request->balance);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountBalance $accountBalance)
    {
        //
    }
}
