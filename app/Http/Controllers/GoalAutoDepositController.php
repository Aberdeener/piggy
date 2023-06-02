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
    public function store(StoreGoalAutoDepositRequest $request)
    {
        $goalAutoDeposit = new GoalAutoDeposit();
        $goalAutoDeposit->goal_id = $request->goal_id;
        $goalAutoDeposit->withdraw_account_id = $request->withdraw_account_id;
        $goalAutoDeposit->start_date = $request->start_date;
        $goalAutoDeposit->frequency = $request->frequency;
        $goalAutoDeposit->amount = $request->amount;
        $goalAutoDeposit->enabled = true;
        $goalAutoDeposit->save();

        if ($goalAutoDeposit->shouldDeposit()) {
            $goalAutoDeposit->deposit();
        }

        return back();
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
    public function update(UpdateGoalAutoDepositRequest $request, GoalAutoDeposit $goalAutoDeposit)
    {
        $goalAutoDeposit->goal_id = $request->goal_id;
        $goalAutoDeposit->withdraw_account_id = $request->withdraw_account_id;
        $goalAutoDeposit->start_date = $request->start_date;
        $goalAutoDeposit->frequency = $request->frequency;
        $goalAutoDeposit->amount = $request->amount;
        $goalAutoDeposit->save();

        return back();
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
