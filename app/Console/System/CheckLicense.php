<?php

namespace App\Console\System;

use App\Services\LicenseService;
use Illuminate\Console\Command;

class CheckLicense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'License check';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $licenseService = new LicenseService();
            $result = $licenseService->register(env('PURCHASE_CODE'), env('LICENSEE_EMAIL'));

            if (!$result->success) {
                $licenseService->save('', '', '');
            }
        } catch(\Exception $e) {
            //
        }
    }
}
