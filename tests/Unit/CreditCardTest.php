<?php

namespace Tests\Unit;

use App\Enums\CreditCardUtilization;
use App\Models\CreditCard;
use Cknow\Money\Money;
use Tests\TestCase;

class CreditCardTest extends TestCase
{

    public function test_utilization_low_below_30_percent(): void
    {
        $creditCard = CreditCard::factory()->create([
            'limit' => Money::USD(10_00),
        ]);

        $creditCard->balances()->create([
            'balance' => Money::USD(2_00),
        ]);

        $this->assertEquals(CreditCardUtilization::Low, $creditCard->utilization());
    }

    public function test_utilization_medium_above_30_percent(): void
    {
        $creditCard = CreditCard::factory()->create([
            'limit' => Money::USD(10_00),
        ]);

        $creditCard->balances()->create([
            'balance' => Money::USD(5_00),
        ]);

        $this->assertEquals(CreditCardUtilization::Medium, $creditCard->utilization());
    }

    public function test_utilization_high_above_70_percent(): void
    {
        $creditCard = CreditCard::factory()->create([
            'limit' => Money::USD(10_00),
        ]);

        $creditCard->balances()->create([
            'balance' => Money::USD(8_00),
        ]);

        $this->assertEquals(CreditCardUtilization::High, $creditCard->utilization());
    }

    public function test_utilization_over_limit(): void
    {
        $creditCard = CreditCard::factory()->create([
            'limit' => Money::USD(10_00),
        ]);

        $creditCard->balances()->create([
            'balance' => Money::USD(12_00),
        ]);

        $this->assertEquals(CreditCardUtilization::OverLimit, $creditCard->utilization());
    }

    public function test_utilization_percentage(): void
    {
        $creditCard = CreditCard::factory()->create([
            'limit' => Money::USD(10_00),
        ]);

        $creditCard->balances()->create([
            'balance' => Money::USD(2_00),
        ]);

        $this->assertEquals('20.00', $creditCard->utilizationPercentage());
    }

    public function test_update_balance_updates_user_net_worth_if_balance_is_different(): void
    {
        $creditCard = CreditCard::factory()->create([
            'limit' => Money::USD(10_00),
        ]);

        $creditCard->balances()->create([
            'balance' => Money::USD(2_00),
        ]);

        $userNetWorth = $creditCard->user->latestNetWorth();

        $creditCard->updateBalance(Money::USD(3_00));

        $newNetWorth = $creditCard->user->latestNetWorth();

        $this->assertNotEquals($userNetWorth, $newNetWorth);
        $this->assertEquals($userNetWorth->subtract(Money::USD(3_00)), $newNetWorth);
    }
}
