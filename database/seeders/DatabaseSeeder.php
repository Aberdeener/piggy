<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Account;
use App\Models\AccountBalance;
use App\Models\CreditCard;
use App\Models\CreditCardBalance;
use App\Models\Goal;
use App\Models\GoalAutoDeposit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Tadhg Boyle',
            'email' => 'tadhgsmboyle@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $this->createForUser($user);
        $user->updateNetWorth();

        User::factory()->count(10)->create()->each(function (User $user) {
            $this->createForUser($user);
            $user->updateNetWorth();
        });
    }

    private function createForUser(User $user): void
    {
        $accounts = Account::factory()->count(3)->sequence(
            ['name' => 'Chequing'],
            ['name' => 'Savings'],
            ['name' => 'Investment'],
        )->create([
            'user_id' => $user->id,
        ]);

        foreach ($accounts as $account) {
            AccountBalance::factory()->create([
                'account_id' => $account->id,
            ]);

            if ($account->name === 'Savings') {
                Goal::factory()->create([
                    'account_id' => $account->id,
                    'name' => 'Emergency Fund',
                ]);
            }

            if ($account->name === 'Investment') {
                Goal::factory()->create([
                    'account_id' => $account->id,
                    'name' => 'Retirement',
                ]);
            }
        }

        foreach ($user->goals as $goal) {
            GoalAutoDeposit::factory()->create([
                'goal_id' => $goal->id,
                'start_date' => $goal->created_at,
                'withdraw_account_id' => $accounts->firstWhere('name', 'Chequing')->id,
            ]);
        }

        $creditCards = CreditCard::factory()->count(2)->sequence(
            ['name' => 'Visa'],
            ['name' => 'Mastercard'],
        )->create([
            'user_id' => $user->id,
        ]);

        foreach ($creditCards as $creditCard) {
            CreditCardBalance::factory()->create([
                'credit_card_id' => $creditCard->id,
            ]);
        }
    }
}
