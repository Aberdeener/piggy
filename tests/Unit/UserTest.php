<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Models\CreditCard;
use App\Models\User;
use Cknow\Money\Money;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function test_net_worth_calculated_correctly(): void
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

        $this->assertEquals(Money::USD(7_50), $user->netWorths()->latest()->first()->amount);
        $this->assertCount(1, $user->netWorths);

        $creditCard->balances()->create([
            'balance' => Money::USD(5_00),
        ]);

        $user->updateNetWorth();

        $this->assertEquals(Money::USD(5_00), $user->netWorths()->latest()->first()->amount);
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

        $this->assertEquals(1, $user->netWorths()->count());
    }

}
