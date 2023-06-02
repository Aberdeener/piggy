<?php

namespace App\Http\Resources;

use App\Models\UserNetWorth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin UserNetWorth */
class UserNetWorthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date' => $this->created_at,
            'amount' => $this->amount,
        ];
    }
}
