<?php

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ReCaptchaValidationPassed implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $reCaptchaSecretKey = config('settings.recaptcha.secret_key');
        // if secret key is not specified then successfully pass validation
        if (!$reCaptchaSecretKey)
            return TRUE;

        // Validate ReCaptcha
        $client = new Client([
            'base_uri' => 'https://google.com/recaptcha/api/'
        ]);
        $response = $client->post('siteverify', [
            'query' => [
                'secret'    => $reCaptchaSecretKey,
                'response'  => $value,
                'remoteip'  => request()->ip(),
            ]
        ]);

        Log::info($response->getBody());

        $responseBody = json_decode($response->getBody());

        return $responseBody->success ?? FALSE;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Please verify that you are a human.');
    }
}
