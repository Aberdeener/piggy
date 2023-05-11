<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCreditCardRequest;
use App\Http\Requests\UpdateCreditCardRequest;
use App\Http\Resources\CreditCardBalanceResource;
use App\Http\Resources\CreditCardResource;
use App\Models\CreditCard;
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CreditCard $creditCard): Response
    {
        return Inertia::render('CreditCard', [
            'creditCard' => new CreditCardResource($creditCard),
            'creditCardBalanceHistory' => CreditCardBalanceResource::collection($creditCard->balances),
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
        //
    }
}
