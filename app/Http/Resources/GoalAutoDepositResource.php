<?php

namespace App\Http\Resources;

use App\Models\GoalAutoDeposit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin GoalAutoDeposit */
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
            'start_date' => $this->start_date,
            'last_deposit_date' => $this->last_deposit_date,
            'next_deposit_date' => $this->nextDepositDate(),
            'enabled' => $this->enabled,
        ];
    }
}
