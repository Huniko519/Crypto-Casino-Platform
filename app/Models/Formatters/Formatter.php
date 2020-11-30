<?php

namespace App\Models\Formatters;

/**
 * Class Formatter - format numbers and dates
 * Each class that uses this trait should define a property called $formats as an associative array of field key and its formatter function.
 * Example: [ 'volume' => 'integer', 'price' => 'decimal' ]
 *
 * @package App\Models\Formatters
 */
trait Formatter
{
    public function getAttribute($key) {
        // values of properties, which start with "_" are supposed to be formatted
        $format = substr($key, 0, 1) == '_';
        if ($format)
            $key = substr($key, 1);

        // call Model's getAttributeValue($key) to get the original value of an attribute
        $value = parent::getAttribute($key);

        // call formatter function if it exists
        if ($format && isset($this->formats) && array_key_exists($key, $this->formats) && method_exists($this, $this->formats[$key]))
            $value = $this->{$this->formats[$key]}($value);

        return $value;
    }

    /**
     * Format integer
     *
     * @param $value
     * @return string
     */
    private function integer($value) {
        return number_format($value, 0, $this->decimalPoint(), $this->thousandsSeparator());
    }

    /**
     * Format decimal
     *
     * @param $value
     * @return string
     */
    private function decimal($value) {
        return number_format($value, $this->decimals(), $this->decimalPoint(), $this->thousandsSeparator());
    }

    /**
     * Format decimal with variable decimals
     *
     * @param $value
     * @return string
     */
    private function variableDecimal($value) {
        $absValue = abs($value);
        if ($absValue >= 10) {
            $decimals = 2;
        } elseif (0.1 <= $absValue && $absValue < 10) {
            $decimals = 4;
        } elseif ($absValue < 0.1) {
            $decimals = 8;
        }

        return number_format($value, $decimals, $this->decimalPoint(), $this->thousandsSeparator());
    }

    private function percentage($value) {
        return $this->decimal($value) . '%';
    }

    private function decimals() {
        return config('settings.format.number.decimals');
    }

    private function decimalPoint() {
        return chr(config('settings.format.number.decimal_point'));
    }

    private function thousandsSeparator() {
        return chr(config('settings.format.number.thousands_separator'));
    }
}