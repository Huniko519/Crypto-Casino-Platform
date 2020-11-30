<?php

namespace App\Http\Requests\Frontend;

use App\Rules\PasswordIsCorrect;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePassword extends FormRequest
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
        // $this->user() = currently logged in user (same as $request->user())

        return [
            'current_password' => [
                'required',
                new PasswordIsCorrect($this->user())
            ],
            'password'              => 'required|confirmed|min:6',
            'password_confirmation' => 'required|min:6'
        ];
    }
}
