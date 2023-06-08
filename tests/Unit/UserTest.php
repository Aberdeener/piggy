<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Models\CreditCard;
use App\Models\User;
use App\Models\UserNetWorth;
use Cknow\Money\Money;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function test_current_net_worth_calculated_correctly(): void
    {
        $user = User::factory()->create();

        $account = Account::factory()->create([
            'user_id' => $user->id,
        ]);
        $account->balances()->create([
            'balance' => Money::USD(10_00),
        ]);

        $creditCard = CreditCard::factory()->create([
            'user_id' => $user->id,
        ]);
        $creditCard->balances()->create([
            'balance' => Money::USD(2_50),
        ]);

        $this->assertEquals(Money::USD(7_50), $user->currentNetWorth());
    }

    public function test_user_has_net_worths_ordered_by_newest_first(): void
    {
        $user = User::factory()->create();

        UserNetWorth::factory()->create([
            'user_id' => $user->id,
            'amount' => $oldestNetWorth = Money::USD(5_00),
            'created_at' => now()->subDay(),
        ]);

        UserNetWorth::factory()->create([
            'user_id' => $user->id,
            'amount' => $newestNetWorth = Money::USD(3_50),
            'created_at' => now()->addDay(),
        ]);

        $this->assertEquals($newestNetWorth, $user->netWorths->first()->amount);
        $this->assertEquals($oldestNetWorth, $user->netWorths->last()->amount);
    }

    public function test_latest_net_worth_returns_0_if_no_net_worths(): void
    {
        $user = User::factory()->create();

        $this->assertEquals(Money::USD(0), $user->latestNetWorth());
    }

    public function test_latest_net_worth_returns_latest_net_worth(): void
    {
        $user = User::factory()->create();

        $user->netWorths()->create([
            'amount' => Money::USD(5_00),
            'created_at' => now()->subDay(),
        ]);

        $user->netWorths()->create([
            'amount' => $latestNetWorth = Money::USD(3_50),
            'created_at' => now()->addDay(),
        ]);

        $this->assertEquals($latestNetWorth, $user->latestNetWorth());
        $this->assertCount(2, $user->netWorths);
    }

    public function test_update_net_worth_updates_net_worth(): void
    {
        $user = User::factory()->create();

        $account = Account::factory()->create([
            'user_id' => $user->id,
        ]);
        $account->balances()->create([
            'balance' => Money::USD(10_00),
        ]);

        $creditCard = CreditCard::factory()->create([
            'user_id' => $user->id,
        ]);
        $creditCard->balances()->create([
            'balance' => Money::USD(2_50),
        ]);

        $user->updateNetWorth();

        $this->assertEquals(Money::USD(7_50), $user->latestNetWorth());
        $this->assertCount(1, $user->netWorths);

        $creditCard->balances()->create([
            'balance' => Money::USD(5_00),
        ]);

        $user->updateNetWorth();

        $this->assertEquals(Money::USD(2_50), $user->latestNetWorth());
        $this->assertCount(2, $user->netWorths);
    }

    public function test_update_net_worth_does_not_update_if_same_amount(): void
    {
        $user = User::factory()->create();

        $account = Account::factory()->create([
            'user_id' => $user->id,
        ]);
        $account->balances()->create([
            'balance' => Money::USD(10_00),
        ]);

        $creditCard = CreditCard::factory()->create([
            'user_id' => $user->id,
        ]);
        $creditCard->balances()->create([
            'balance' => Money::USD(2_50),
        ]);

        $user->updateNetWorth();
        $user->updateNetWorth();

        $this->assertCount(1, $user->netWorths);
    }

}
