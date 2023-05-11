<?php

namespace App\Http\Controllers;

use App\Models\UserNetWorth;
use Illuminate\Http\Request;

class UserUpdateNetWorthController extends Controller
{
    public function __invoke(Request $request)
    {
        $latestStoredNetWorth = $request->user()->netWorths()->latest()->first()->amount->getAmount();
        $currentNetWorth = $request->user()->currentNetWorth()->getAmount();

        if ($latestStoredNetWorth === $currentNetWorth) {
            return;
        }

        $netWorth = new UserNetWorth();
        $netWorth->user_id = $request->user()->id;
        $netWorth->amount = $currentNetWorth;
        $netWorth->save();
    }
}
