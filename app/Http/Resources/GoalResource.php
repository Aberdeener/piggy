<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'current_amount' => $this->current_amount,
            'status' => $this->status(),
            'completion_percentage' => $this->completionPercentage(),
            'account' => new AccountResource($this->account),
            'auto_deposits' => GoalAutoDepositResource::collection($this->autoDeposits),
            'projected_total_by_target_date' => $this->projectedTotal(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
