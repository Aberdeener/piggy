<?php

namespace Tests\Unit;

use App\Enums\GoalAutoDepositFrequency;
use App\Enums\GoalStatus;
use App\Models\Account;
use App\Models\Goal;
use Cknow\Money\Money;
use Tests\TestCase;

class GoalTest extends TestCase
{
    public function test_status_complete_when_latest_balance_is_target(): void
    {
        $goal = Goal::factory()->create([
            'target_amount' => Money::USD(10_00),
            'target_date' => now()->addDays(30),
        ]);

        $goal->updateBalance(Money::USD(10_00));

        $this->assertEquals(GoalStatus::Completed, $goal->status());
    }

    public function test_status_off_track_when_no_autodeposits(): void
    {
        $goal = Goal::factory()->create([
            'target_amount' => Money::USD(10_00),
            'target_date' => now()->addDays(30),
        ]);

        $this->assertEquals(GoalStatus::OffTrack, $goal->status());
    }

    public function test_status_off_track_when_has_insignificant_autodeposits(): void
    {
        $goal = Goal::factory()->create([
            'target_amount' => Money::USD(10_00),
            'target_date' => now()->addDays(30),
        ]);

        $goal->autoDeposits()->create([
            'frequency' => GoalAutoDepositFrequency::Daily,
            'amount' => Money::USD(0_10),
            'withdraw_account_id' => Account::factory()->create([
                'user_id' => $goal->user->id,
            ])->id,
            'start_date' => now(),
            'enabled' => true,
        ]);

        $this->assertEquals(GoalStatus::OffTrack, $goal->status());
    }

    public function test_status_on_track_when_has_autodeposits(): void
    {
        $goal = Goal::factory()->create([
            'target_amount' => Money::USD(10_00),
            'target_date' => now()->addDays(30),
        ]);

        $goal->autoDeposits()->create([
            'frequency' => GoalAutoDepositFrequency::Daily,
            'amount' => Money::USD(5_00),
            'withdraw_account_id' => Account::factory()->create([
                'user_id' => $goal->user->id,
            ])->id,
            'start_date' => now(),
            'enabled' => true,
        ]);

        $this->assertEquals(GoalStatus::OnTrack, $goal->status());
    }

    public function test_projected_total_when_no_autodeposits(): void
    {
        $goal = Goal::factory()->create([
            'target_amount' => Money::USD(10_00),
            'target_date' => now()->addDays(30),
        ]);
        $goal->updateBalance(Money::USD(5_00));

        $this->assertEquals(Money::USD(5_00), $goal->projectedTotal());
    }

    public function test_projected_total_when_has_autodeposits(): void
    {
        $goal = Goal::factory()->create([
            'target_amount' => Money::USD(10_00),
            'target_date' => now()->addDays(30),
        ]);
        $goal->updateBalance(Money::USD(5_00));

        $goal->autoDeposits()->create([
            'frequency' => GoalAutoDepositFrequency::Daily,
            'amount' => Money::USD(5_00),
            'withdraw_account_id' => Account::factory()->create([
                'user_id' => $goal->user->id,
            ])->id,
            'start_date' => now(),
            'enabled' => true,
        ]);

        // 30 days * $5 = $150 + $5 = $155
        $this->assertEquals(Money::USD(155_00), $goal->projectedTotal());
    }

    public function test_projected_total_when_has_disabled_autodeposits(): void
    {
        $goal = Goal::factory()->create([
            'target_amount' => Money::USD(10_00),
            'target_date' => now()->addDays(30),
        ]);
        $goal->updateBalance(Money::USD(5_00));

        $goal->autoDeposits()->create([
            'frequency' => GoalAutoDepositFrequency::Daily,
            'amount' => Money::USD(5_00),
            'withdraw_account_id' => Account::factory()->create([
                'user_id' => $goal->user->id,
            ])->id,
            'start_date' => now(),
            'enabled' => false,
        ]);

        $this->assertEquals(Money::USD(5_00), $goal->projectedTotal());
    }

    public function test_completion_percentage(): void
    {
        $goal = Goal::factory()->create([
            'target_amount' => Money::USD(10_00),
            'target_date' => now()->addDays(30),
        ]);
        $goal->updateBalance(Money::USD(5_00));

        $this->assertEquals('50.00', $goal->completionPercentage());
    }

    public function test_completion_percentage_over_1_hundred(): void
    {
        $goal = Goal::factory()->create([
            'target_amount' => Money::USD(10_00),
            'target_date' => now()->addDays(30),
        ]);
        $goal->updateBalance(Money::USD(11_00));

        $this->assertEquals('110.00', $goal->completionPercentage());
    }
}
