<?php

namespace Database\Factories;

use App\Enums\GoalAutoDepositFrequency;
use App\Models\Account;
use App\Models\Goal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GoalAutoDeposit>
 */
class GoalAutoDepositFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $goal = Goal::factory()->create();

        return [
            'goal_id' => $goal->id,
            'withdraw_account_id' => Account::factory()->create([
                'user_id' => $goal->user->id,
            ])->id,
            'enabled' => true,
            'amount' => $this->faker->numberBetween(1000, 100000),
            'start_date' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'frequency' => strtolower($this->faker->randomElement(GoalAutoDepositFrequency::cases())->name),
        ];
    }
}
