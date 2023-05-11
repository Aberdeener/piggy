<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditCardBalanceUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'balance' => ['required', 'numeric'],
        ];
    }
}
