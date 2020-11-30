<?php

namespace App\Http\Requests\Backend;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // $this->user = user being checked
        // $this->user() = currently logged in user
        return [
            'name'          => 'required|string|max:100|unique:users,name,' . $this->user->id . ',id', // don't validate name uniqueness if it's not updated
            'email'         => 'required|string|email|max:255|unique:users,email,' . $this->user->id . ',id', // don't validate email uniqueness if it's not updated
            'password'      => 'nullable|string|min:6',
            'role' => [
                'required',
                Rule::in(User::roles()),
            ],
            'status' => [
                'required',
                Rule::in(User::statuses()),
            ],
            'individual_referral_bonuses'   => 'string|nullable',
            'referee_sign_up_credits'       => 'integer|nullable|min:0|max:4294967295', // unsigned INT
            'referrer_sign_up_credits'      => 'integer|nullable|min:0|max:4294967295', // unsigned INT
            'referrer_deposit_pct'          => 'numeric|nullable|min:0|max:999999',
            'referrer_game_bet_pct'         => 'numeric|nullable|min:0|max:999999',
        ];
    }
}
