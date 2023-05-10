<?php

namespace Tests\Unit;

use App\Enums\GoalAutoDepositFrequency;
use App\Models\Goal;
use App\Models\GoalAutoDeposit;
use Tests\TestCase;

class GoalAutoDepositTest extends TestCase
{
    public function test_should_deposit(): void
    {
        $goalAutoDeposit = GoalAutoDeposit::factory()->create([
            'frequency' => GoalAutoDepositFrequency::Daily,
            'start_date' => now()->subDay(),
        ]);

        $this->assertTrue($goalAutoDeposit->shouldDeposit());
    }

    public function test_deposit_updates_goal_current_balance(): void
    {
        $goalAutoDeposit = GoalAutoDeposit::factory()->create([
            'frequency' => GoalAutoDepositFrequency::Daily,
            'start_date' => now()->subDay(),
        ]);

        $goalAutoDeposit->deposit();

        $this->assertEquals($goalAutoDeposit->goal->current_amount, $goalAutoDeposit->amount);
    }

    public function test_deposit_updates_last_deposit_date(): void
    {
        $goalAutoDeposit = GoalAutoDeposit::factory()->create([
            'frequency' => GoalAutoDepositFrequency::Daily,
            'start_date' => now()->subDay(),
        ]);

        $goalAutoDeposit->deposit();

        $this->assertEquals($goalAutoDeposit->last_deposit_date->startOfDay(), now()->startOfDay());
    }

    public function test_next_deposit_date_calculated_correctly(): void
    {
        $frequencies = [
            GoalAutoDepositFrequency::Daily->value => 'addDay',
            GoalAutoDepositFrequency::Weekly->value => 'addWeek',
            GoalAutoDepositFrequency::Monthly->value => 'addMonth',
            GoalAutoDepositFrequency::Yearly->value => 'addYear',
        ];

        foreach ($frequencies as $frequency => $method) {
            $goalAutoDeposit = GoalAutoDeposit::factory()->create([
                'frequency' => $frequency,
                'start_date' => now(),
            ]);

            $this->assertEquals($goalAutoDeposit->nextDepositDate()->startOfDay(), now()->{$method}()->startOfDay());
        }
    }

    public function test_determine_iterations(): void
    {
        $goal = Goal::factory()->create([
            'target_date' => now()->addDays(10),
        ]);

        $goalAutoDeposit = GoalAutoDeposit::factory()->create([
            'goal_id' => $goal->id,
            'frequency' => GoalAutoDepositFrequency::Daily,
            'start_date' => now(),
        ]);

        $this->assertEquals(10, $goalAutoDeposit->determineIterationsUntil($goalAutoDeposit->goal->target_date));
    }
}
