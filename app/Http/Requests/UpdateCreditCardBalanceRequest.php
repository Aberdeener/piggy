<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCreditCardBalanceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'balance' => ['required', 'numeric'],
        ];
    }
}
