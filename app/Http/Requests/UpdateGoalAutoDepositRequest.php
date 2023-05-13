<?php

namespace App\Http\Requests;

use App\Enums\GoalAutoDepositFrequency;
use App\Models\Goal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGoalAutoDepositRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'goal_id' => [
                'required',
                // TODO: user_id on goals table
//                Rule::exists('goals', 'id')
//                    ->where('user_id', $this->user()->id)
            ],
            'withdraw_account_id' => [
                'required',
                Rule::exists('accounts', 'id')
                    ->whereNot('id', Goal::find($this->get('goal_id'))->account_id)
                    ->where('user_id', $this->user()->id)
            ],
            'start_date' => ['required', 'date', 'after_or_equal::today'],
            'frequency' => ['required', Rule::enum(GoalAutoDepositFrequency::class)],
            'amount' => ['required', 'numeric', 'min:0'],
        ];
    }
}
