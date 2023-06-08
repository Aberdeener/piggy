<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCreditCardRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('credit_cards')->where('user_id', request()->user()->id)->whereNull('deleted_at')],
            'balance' => ['required', 'numeric', 'min:0'],
            'limit' => ['required', 'numeric', 'min:0'],
        ];
    }
}
