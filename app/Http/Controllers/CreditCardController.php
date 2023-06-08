<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCreditCardRequest;
use App\Http\Resources\BalanceResource;
use App\Http\Resources\CreditCardResource;
use App\Models\CreditCard;
use Cknow\Money\Money;
use Inertia\Inertia;
use Inertia\Response;

class CreditCardController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(CreditCard::class);
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
    public function store(StoreCreditCardRequest $request)
    {
        $creditCard = new CreditCard();
        $creditCard->name = $request->name;
        $creditCard->limit = Money::USD($request->limit);
        $creditCard->user_id = request()->user()->id;
        $creditCard->save();

        $creditCard->balances()->create([
            'balance' => Money::USD($request->balance),
        ]);

        $creditCard->user->updateNetWorth();

        return to_route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(CreditCard $creditCard): Response
    {
        return Inertia::render('CreditCards/Show', [
            'creditCard' => new CreditCardResource($creditCard),
            'creditCardBalanceHistory' => BalanceResource::collection($creditCard->balances),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CreditCard $creditCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCreditCardRequest $request, CreditCard $creditCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CreditCard $creditCard)
    {
        $creditCard->delete();

        $creditCard->user->updateNetWorth();

        return to_route('dashboard');
    }
}
