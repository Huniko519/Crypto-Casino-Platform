<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Utils
{
    /**
     * Get a daily time series of a given size
     *
     * @param $size
     * @return Collection
     */
    public static function timeSeries($size): Collection
    {
        $timeSeries = new Collection();

        for ($i=$size; $i>=0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $timeSeries->put($date, [
                'date'      => $date,
                'value'     => 0
            ]);
        }

        return $timeSeries;
    }

    /**
     * Get class ID
     *
     * @param $object
     * @return string
     */
    public static function classId($object): string
    {
        // Str::kebab('Dice3D') = 'dice3-d', so need to add extra preg_replace()
        return preg_replace('#([0-9]+)-#', '-$1', Str::kebab(class_basename($object)));
    }
}
