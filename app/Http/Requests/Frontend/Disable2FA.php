<?php

namespace App\Http\Requests\Frontend;

use App\Rules\PasswordIsCorrect;
use App\Rules\TotpIsCorrect;
use Illuminate\Foundation\Http\FormRequest;

class Disable2Fa extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TOTP token is NOT NULL (2FA is enabled)
        return !!$this->user()->totp_secret;
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
            'totp' => [
                'required',
                new TotpIsCorrect($this->user()->totp_secret)
            ],
            'password' => [
                'required',
                new PasswordIsCorrect($this->user())
            ]
        ];
    }
}
