<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGoalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique('goals')->whereIn('account_id', $this->user()->accounts->pluck('id')->toArray())->whereNull('deleted_at')],
            'target_amount' => ['required', 'numeric', 'min:0'],
            'target_date' => ['required', 'date', 'after:today'],
            'account_id' => ['required', Rule::exists('accounts', 'id')->where('user_id', $this->user()->id)],
            'use_account_balance_to_start' => ['boolean'],
        ];
    }
}
