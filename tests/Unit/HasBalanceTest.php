<?php

namespace Tests\Unit;

use App\Models\Concerns\HasBalance;
use App\Models\CreditCard;
use Cknow\Money\Money;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Mockery;
use Mockery\MockInterface;
use RuntimeException;
use Tests\TestCase;

class HasBalanceTest extends TestCase
{
    public function test_model_has_balances_ordered_by_newest_first(): void
    {
        $model = $this->model();

        $model->balances()->create([
            'balance' => $oldestBalance = Money::USD(5_00),
            'created_at' => now()->subDay(),
        ]);

        $model->balances()->create([
            'balance' => $newestBalance = Money::USD(3_50),
            'created_at' => now()->addDay(),
        ]);

        $this->assertNotNull($model->balances);
        $this->assertCount(2, $model->balances);
        $this->assertEquals($newestBalance, $model->balances->first()->balance);
        $this->assertEquals($oldestBalance, $model->balances->last()->balance);
    }

    public function test_latest_balance_returns_0_if_balancess(): void
    {
        $model = $this->model();

        $this->assertEquals(Money::USD(0), $model->latestBalance());
    }

    public function test_latest_balance_returns_balance(): void
    {
        $model = $this->model();

        $model->balances()->create([
            'balance' => Money::USD(5_00),
            'created_at' => now()->subDay(),
        ]);

        $model->balances()->create([
            'balance' => $latestBalance = Money::USD(3_50),
            'created_at' => now()->addDay(),
        ]);

        $this->assertEquals($latestBalance, $model->latestBalance());
        $this->assertCount(2, $model->balances);
    }

    public function test_update_balance_does_not_create_new_balance_if_balance_is_the_same(): void
    {
        $model = $this->model();

        $model->balances()->create([
            'balance' => Money::USD(2_00),
        ]);

        $model->updateBalance(Money::USD(2_00));

        $this->assertEquals(Money::USD(2_00), $model->latestBalance());
        $this->assertCount(1, $model->balances);
    }

    public function test_update_balance_creates_new_balance_if_balance_is_different(): void
    {
        $model = $this->model();

        $model->balances()->create([
            'balance' => Money::USD(2_00),
        ]);

        $model->updateBalance($latestBalance = Money::USD(3_00));

        $this->assertEquals($latestBalance, $model->latestBalance());
        $this->assertCount(2, $model->balances);
    }

    public function test_update_balance_calls_on_balance_updated_if_balance_is_different(): void
    {
        $this->markTestIncomplete();
//        $model = $this->model();
//
//        $difference = Money::USD(1_00);
//
//        // TODO why does this not work?
//        // $mock = Mockery::mock($model)->makePartial();
//        // $mock->expects('onBalanceUpdated')->once()->with($difference);
//
//        $model->balances()->create([
//            'balance' => $originalBalance = Money::USD(2_00),
//        ]);
//
//        $model->updateBalance($originalBalance->add($difference));
//
//        $this->expectException(RuntimeException::class);
    }

    /**
     * @return Model&HasBalance
     */
    private function model(): Model
    {
        return (new class extends Model {
            use HasBalance;

            public function onBalanceUpdated(Money $difference): void {
                // ...
            }

        })->forceFill([
            'id' => 1,
        ]);
    }
}
