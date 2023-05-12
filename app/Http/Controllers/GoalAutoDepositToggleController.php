<?php

namespace App\Http\Controllers;

use App\Models\GoalAutoDeposit;

class GoalAutoDepositToggleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GoalAutoDeposit $goalAutoDeposit)
    {
        $goalAutoDeposit->update([
            'enabled' => !$goalAutoDeposit->enabled,
        ]);

        return back();
    }
}
