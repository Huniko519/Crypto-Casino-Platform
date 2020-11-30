<?php

namespace Packages\Installer\Services;

use GuzzleHttp\Client;

class InstallerService
{
    public function register()
    {
        $client = new Client(['base_uri' => config('app.api.products.base_uri')]);

        $response = $client->request('POST', 'installations/register', [
            'form_params' => [
                'hash'      => config('app.hash'),
                'version'   => config('app.version'),
                'domain'    => request()->getHost(),
                'info'      => [
                    'code'  => env('PURCHASE_CODE'),
                    'email' => env('LICENSEE_EMAIL'),
                ],
            ],
        ]);

        return json_decode($response->getBody()->getContents());
    }
}
