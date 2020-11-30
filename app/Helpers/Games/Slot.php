<?php

namespace App\Helpers\Games;

class Slot
{
    /**
     * @var array - number of symbols on each reel, e.g. [7, 8, 8, 10, 8]. Array length is the total number of reels.
     */
    private $reels;

    /**
     * @var array - spinned reels positions, e.g. [3, 0, 5, 7, 4]
     */
    private $reelPositions;

    public function __construct(array $reels)
    {
        $this->reelPositions = array_pad([], count($reels), 0); // initial reels positions
        $this->reels = $reels;

        return $this;
    }

    /**
     * Randomly spin the reels
     *
     * @return Slot
     */
    public function spin(): Slot
    {
        $this->reelPositions = array_map(function($reelSymbolsCount) {
            return random_int(0, $reelSymbolsCount-1);
        }, $this->reels);

        return $this;
    }

    /**
     * Shift each reel a specific number of times
     *
     * @param array $shifts
     * @return Slot
     */
    public function shift(array $shifts): Slot
    {
        $this->reelPositions = array_map(function ($reelSymbolsCount, $reelPosition, $shift) {
            $adjustedShift = $reelSymbolsCount < $shift ? $shift % $reelSymbolsCount : $shift;
            return $reelPosition + $adjustedShift >= $reelSymbolsCount ? $reelPosition - $reelSymbolsCount + $adjustedShift : $reelPosition + $adjustedShift;
        }, $this->reels, $this->reelPositions, $shifts);

        return $this;
    }

    /**
     * Get reels
     *
     * @return array
     */
    public function getReels(): array
    {
        return $this->reels;
    }

    /**
     * Return current reels positions
     *
     * @return array
     */
    public function getReelsPositions(): array
    {
        return $this->reelPositions;
    }

    /**
     * Set reels to specific positions passed in the input array
     *
     * @param array $reelsPositions
     * @return Slot
     */
    public function setReelsPositions(array $reelsPositions): Slot
    {
        if (!empty($reelsPositions) && count($reelsPositions) == count($this->reels)) {
            $this->reelPositions = $reelsPositions;
            // shift reels by 0 values to ensure proper indexing of reels (important when switching between different Slots games)
            $this->shift(array_pad([], count($this->reels), 0));
        }

        return $this;
    }
}