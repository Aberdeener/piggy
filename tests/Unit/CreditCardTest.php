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

    public function test_update_balance_does_not_create_new_balance_if_balance_is_the_same(): void
    {
        $creditCard = CreditCard::factory()->create([
            'limit' => Money::USD(10_00),
        ]);

        $creditCard->balances()->create([
            'balance' => Money::USD(2_00),
        ]);

        $creditCard->updateBalance(Money::USD(2_00));

        $this->assertCount(1, $creditCard->balances);
    }

    public function test_update_balance_creates_new_balance_if_balance_is_different(): void
    {
        $creditCard = CreditCard::factory()->create([
            'limit' => Money::USD(10_00),
        ]);

        $creditCard->balances()->create([
            'balance' => Money::USD(2_00),
        ]);

        $creditCard->updateBalance(Money::USD(3_00));

        $this->assertCount(2, $creditCard->balances);
    }
}
