<?php

namespace Tests\Unit;

use App\Models\Account;
use Cknow\Money\Money;
use Tests\TestCase;

class AccountTest extends TestCase
{
    public function test_update_balance_updates_user_net_worth_if_balance_is_different(): void
    {
        $account = Account::factory()->create();

        $account->balances()->create([
            'balance' => Money::USD(2_00),
        ]);

        $userNetWorth = $account->user->latestNetWorth();

        $account->updateBalance(Money::USD(3_00));

        $newNetWorth = $account->user->latestNetWorth();

        $this->assertNotEquals($userNetWorth, $newNetWorth);
        $this->assertEquals($userNetWorth->add(Money::USD(3_00)), $newNetWorth);
    }
}
