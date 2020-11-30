<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ConfigVariablesAreSet implements Rule
{
    private $vars = [];
    private $missingVars = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $vars)
    {
        $this->vars = $vars;
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
        // loop through config vars and check that all required variables are set
        foreach ($this->vars as $var) {
            $value = config($var);
            if ($value == '' || is_null($value)) {
                $this->missingVars[] = $var;
            }
        }

        return empty($this->missingVars);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Please check that the following configuration variables are set: :vars.', ['vars' => implode(', ', $this->missingVars)]);
    }
}
