<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Goal>
 */
class GoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'name' => $this->faker->word,
            'account_id' => Account::factory()->create([
                'user_id' => $user->id,
            ])->id,
            'target_amount' => $this->faker->numberBetween(10000, 100000),
            'target_date' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
        ];
    }
}
