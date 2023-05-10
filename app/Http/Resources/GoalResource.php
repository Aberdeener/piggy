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
            'auto_deposits' => GoalAutoDepositResource::collection($this->autoDeposits),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
