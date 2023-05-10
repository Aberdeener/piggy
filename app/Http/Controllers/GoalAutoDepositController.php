<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoalAutoDepositRequest;
use App\Http\Requests\UpdateGoalAutoDepositRequest;
use App\Models\GoalAutoDeposit;
use Illuminate\Http\JsonResponse;

class GoalAutoDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(request()->user()->goalAutoDeposits()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoalAutoDepositRequest $request): JsonResponse
    {
        $goalAutoDeposit = new GoalAutoDeposit($request->validated());
        $goalAutoDeposit->user()->associate($request->user());
        $goalAutoDeposit->save();

        return response()->json($goalAutoDeposit);
    }

    /**
     * Display the specified resource.
     */
    public function show(GoalAutoDeposit $goalAutoDeposit): JsonResponse
    {
        return response()->json($goalAutoDeposit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoalAutoDepositRequest $request, GoalAutoDeposit $goalAutoDeposit): JsonResponse
    {
        $goalAutoDeposit->update($request->validated());

        return response()->json($goalAutoDeposit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoalAutoDeposit $goalAutoDeposit): JsonResponse
    {
        $goalAutoDeposit->delete();

        return response()->json($goalAutoDeposit);
    }
}
