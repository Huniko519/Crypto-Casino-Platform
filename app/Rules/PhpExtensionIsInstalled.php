<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhpExtensionIsInstalled implements Rule
{
    private $extension;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $extension)
    {
        $this->extension = $extension;
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
        return extension_loaded($this->extension);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('PHP extension ":ext" should be installed and enabled on your server.', ['ext' => $this->extension]);
    }
}
