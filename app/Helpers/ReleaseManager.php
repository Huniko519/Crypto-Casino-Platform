<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class ReleaseManager
{
    /**
     * Get releases info
     *
     * @param bool|FALSE $forceRefresh
     * @return mixed
     */
    public function getInfo($forceRefresh = FALSE): \stdClass
    {
        if ($forceRefresh)
            Cache::forget('releases');

        return Cache::remember('releases', 7200, function() { // cache releases info for 24 hours
            $client = new Client(['base_uri' => config('app.api.releases.base_uri')]);
            $response = $client->request('GET', 'releases');

            return json_decode($response->getBody()->getContents());
        });
    }
}
