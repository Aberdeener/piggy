<?php

namespace Database\Factories;

use App\Enums\GoalAutoDepositFrequency;
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
        return [
            'amount' => $this->faker->numberBetween(1000, 100000),
            'frequency' => strtolower($this->faker->randomElement(GoalAutoDepositFrequency::cases())->name),
        ];
    }
}
