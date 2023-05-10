<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoalAutoDepositResource extends JsonResource
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
            'amount' => $this->amount,
            'withdraw_account_id' => $this->withdraw_account_id,
            'frequency' => $this->frequency,
            'last_deposit_date' => $this->last_deposit_date,
            'next_deposit_date' => $this->nextDepositDate(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
