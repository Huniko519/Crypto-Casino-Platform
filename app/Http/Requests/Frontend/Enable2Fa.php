<?php

namespace App\Http\Requests\Frontend;

use App\Rules\TotpIsCorrect;
use Illuminate\Foundation\Http\FormRequest;

class Enable2Fa extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TOTP token is NULL (2FA is disabled)
        return !$this->user()->totp_secret;
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
            'secret'    => 'required|string|size:32',
            'totp'      => [
                'required',
                new TotpIsCorrect($this->secret),
            ]
        ];
    }
}
