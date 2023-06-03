<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoalRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\GoalResource;
use App\Models\Goal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Inertia\Inertia;
use Inertia\Response;

class GoalController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Goal::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoalRequest $request)
    {
        $goal = new Goal($request->validated());
        if ($request->use_account_balance_to_start) {
            // TODO: should we use the same historic balance system as accounts/cc so we can graph it?
            $goal->current_amount = $goal->account->latestBalance();
        }

        $goal->save();

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Goal $goal): Response
    {
        return Inertia::render('Goals/Show', [
            'goal' => new GoalResource($goal),
            'accounts' => AccountResource::collection($goal->user->accounts),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoalRequest $request, Goal $goal): JsonResource
    {
        $goal->update($request->validated());

        return new GoalResource($goal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Goal $goal)
    {
        $goal->delete();

        return to_route('dashboard');
    }
}
