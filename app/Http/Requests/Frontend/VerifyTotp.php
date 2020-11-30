<?php

namespace App\Http\Requests\Frontend;

use App\Rules\TotpIsCorrect;
use Illuminate\Foundation\Http\FormRequest;

class VerifyTotp extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !$this->session()->has('2fa_passed');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'totp' => [
                'required',
                new TotpIsCorrect($this->user()->totp_secret)
            ],
        ];
    }
}
