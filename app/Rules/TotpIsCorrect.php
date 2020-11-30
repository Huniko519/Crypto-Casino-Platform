<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use PragmaRX\Google2FA\Google2FA;

class TotpIsCorrect implements Rule
{
    private $secret;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $google2fa = new Google2FA();
        return $value && $google2fa->verifyKey($this->secret, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Incorrect one-time password.');
    }
}
