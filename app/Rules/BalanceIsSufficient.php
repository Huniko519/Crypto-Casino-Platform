<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BalanceIsSufficient implements Rule
{
    private $user;
    private $requiredAmount;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($requiredAmount)
    {
        $this->user = auth()->user();
        $this->requiredAmount = floatval($requiredAmount);
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
        return $this->user->account->balance >= $this->requiredAmount;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Insufficient balance to perform this operation.');
    }
}
