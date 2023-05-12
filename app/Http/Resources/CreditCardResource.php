<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditCardResource extends JsonResource
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
            'balance' => $this->latestBalance(),
            'limit' => $this->limit,
            'utilization' => $this->utilization(),
            'utilization_percentage' => $this->utilizationPercentage(),
        ];
    }
}
