<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountResource;
use App\Http\Resources\CreditCardResource;
use App\Http\Resources\GoalResource;
use App\Http\Resources\UserNetWorthResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Dashboard', [
            'netWorth' => [
                'current' => $user->latestNetWorth(),
                'history' => UserNetWorthResource::collection($user->netWorths),
            ],
            'accounts' => AccountResource::collection($user->accounts),
            'creditCards' => CreditCardResource::collection($user->creditCards),
            'goals' => GoalResource::collection($user->goals),
        ]);
    }
}
