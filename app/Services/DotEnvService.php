<?php

namespace App\Services;

use Dotenv\Dotenv;

class DotEnvService
{
    const ENV = '.env';
    const ENV_DEFAULT = '.env.install';
    private $envFilePath;

    public function __construct()
    {
        $this->envFilePath = base_path() . '/' . self::ENV;
    }

    /**
     * Create .env file from the default .env.install provided with the app
     * @return bool
     */
    public function createFromDefault() {
        $defaultEnvFilePath = base_path() . '/' . self::ENV_DEFAULT;
        if (file_exists($defaultEnvFilePath) && is_writable(base_path()))
            return copy($defaultEnvFilePath, $this->envFilePath);

        return FALSE;
    }

    /**
     * Get config variables from env file
     * @return array
     */
    public function load() {
        try {
            $dotenv = new Dotenv(base_path());
            $envString = implode("\n", $dotenv->load());
            if (preg_match_all('#^(.*)=(.*)#m', $envString, $matches))
                return array_combine($matches[1], $matches[2]);
        } catch (\Exception $e) {
            // do nothing
        }

        return FALSE;
    }

    /**
     * Save config variables to env file
     * @param array $params
     * @return bool
     */
    public function save(array $params)
    {
        // include string into double quotes if it contains white spaces
        $quoteString = function($string) {
            return strpos($string, ' ') !== FALSE && strpos($string, '\\') === FALSE
                ? '"' . addcslashes($string, '"') . '"'
                : $string;
        };

        if (!empty($params) && is_array($params) && is_writable($this->envFilePath)) {
            return file_put_contents($this->envFilePath, implode("\n", array_map(function ($key, $value) use($quoteString) {
                return $key . '=' . $quoteString(
                    is_array($value) ?
                    json_encode(array_map(function($v) { return in_array($v[0], ['[','{']) ? json_decode($v) : $v; }, $value)) :
                    $value
                );
            }, array_keys($params), $params)));
        }

        return FALSE;
    }
}
