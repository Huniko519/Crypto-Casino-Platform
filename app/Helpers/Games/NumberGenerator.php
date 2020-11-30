<?php

namespace App\Helpers\Games;

class NumberGenerator
{
    private $min;
    private $max;
    private $number;

    public function __construct(int $min = 0, int $max = 9999)
    {
        $this->min = $min;
        $this->max = $max;

        return $this;
    }

    /**
     * Generate a random number
     *
     * @return NumberGenerator
     */
    public function generate(): NumberGenerator
    {
        $this->number = random_int($this->min, $this->max);

        return $this;
    }

    /**
     * Shift current random number
     *
     * @param int $shift
     * @return NumberGenerator
     */
    public function shift(int $shift): NumberGenerator
    {
        if ($shift > $this->max)
            $shift = $shift % ($this->max - $this->min + 1);

        $this->number = $this->number + $shift <= $this->max ?
            $this->number + $shift :
            $this->min + ($this->number + $shift - $this->max) - 1;

        return $this;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): NumberGenerator
    {
        if ($number > $this->max) {
            $number = $this->min + $number % ($this->max - $this->min + 1);
        }

        $this->number = $number;

        return $this;
    }

    public function getMin()
    {
        return $this->min;
    }

    public function getMax()
    {
        return $this->max;
    }
}
