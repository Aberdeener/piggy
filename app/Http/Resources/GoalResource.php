<?php

namespace App\Http\Resources;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Goal */
class GoalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'target_date' => $this->target_date,
            'target_amount' => $this->target_amount,
            'balance' => $this->latestBalance(),
            'status' => $this->status(),
            'completion_percentage' => $this->completionPercentage(),
            'account' => new AccountResource($this->account),
            'auto_deposits' => GoalAutoDepositResource::collection($this->autoDeposits),
            'projected_status' => $this->projectedStatus(),
            'projected_total_by_target_date' => $this->projectedTotal(),
            'projected_completion_percentage' => $this->projectedCompletionPercentage(),
        ];
    }
}
