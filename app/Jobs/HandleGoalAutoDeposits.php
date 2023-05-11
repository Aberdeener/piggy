<?php

namespace App\Jobs;

use App\Models\Goal;
use App\Models\GoalAutoDeposit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleGoalAutoDeposits implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Goal::query()->chunk(100, static function (Goal $goal) {
            $goal->autoDeposits->each(static function (GoalAutoDeposit $autoDeposit) {
                if ($autoDeposit->shouldDeposit()) {
                    $autoDeposit->deposit();
                }
            });
        });
    }
}
