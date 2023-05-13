<?php

namespace App\Http\Requests;

use App\Models\Goal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGoalAutoDepositRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'withdraw_account_id' => [
                'required',
                Rule::exists('accounts', 'id')
                    ->whereNot('account_id', Goal::find($this->get('goal_id'))->account_id)
                    ->where('user_id', $this->user()->id)
            ],
            'start_date' => ['required', 'date'],
            'frequency' => ['required', 'in:weekly,biweekly,monthly'],
        ];
    }
}
