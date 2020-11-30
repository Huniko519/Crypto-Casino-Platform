<?php

namespace App\Services;

use GuzzleHttp\Client;

class LicenseService
{
    /**
     * Register a license
     *
     * @param $purchaseCode
     * @param $email
     * @return mixed
     */
    public function register($purchaseCode, $email)
    {
        $client = new Client(['base_uri' => config('app.api.products.base_uri')]);
        $response = $client->request('POST', 'licenses/register', [
            'form_params' => [
                'code' => $purchaseCode,
                'email' => $email,
                'domain' => request()->getHost(),
                'hash' => config('app.hash')
            ]
        ]);

        return \GuzzleHttp\json_decode($response->getBody()->getContents());
    }

    public function download($purchaseCode, $email, $hash, $version)
    {
        $client = new Client(['base_uri' => config('app.api.products.base_uri')]);
        $response = $client->request('POST', 'products/download', [
            'form_params' => [
                'code' => $purchaseCode,
                'email' => $email,
                'domain' => request()->getHost(),
                'hash' => $hash,
                'version' => $version
            ],
        ]);

        return $response->getHeaderLine('Content-Type') == 'application/zip' ?
            $response->getBody()->getContents() :
            \GuzzleHttp\json_decode($response->getBody()->getContents());
    }

    /**
     * Save registration data
     *
     * @param $data
     */
    public function save($purchaseCode, $email, $hash)
    {
        $dotEnvService = new DotEnvService();
        if ($env = $dotEnvService->load()) {
            $env['PURCHASE_CODE'] = $purchaseCode;
            $env['SECURITY_HASH'] = $hash;
            $env['LICENSEE_EMAIL'] = $email;
            $dotEnvService->save($env);
        }
    }
}
