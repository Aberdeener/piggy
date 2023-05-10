<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\UpdateGoalRequest;
use App\Models\Goal;
use Illuminate\Http\JsonResponse;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(request()->user()->goals()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoalRequest $request): JsonResponse
    {
        $goal = new Goal($request->validated());
        $goal->user()->associate($request->user());
        $goal->save();

        return response()->json($goal);
    }

    /**
     * Display the specified resource.
     */
    public function show(Goal $goal): JsonResponse
    {
        return response()->json($goal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoalRequest $request, Goal $goal): JsonResponse
    {
        $goal->update($request->validated());

        return response()->json($goal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Goal $goal): JsonResponse
    {
        $goal->delete();

        return response()->json($goal);
    }
}
