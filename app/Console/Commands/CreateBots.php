<?php

namespace App\Console\Commands;

use App\Events\CommandExecuted;
use App\Services\UserService;
use Illuminate\Console\Command;

class CreateBots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:bots {count=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create bots';

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
        for ($i=0; $i < intval($this->argument('count')); $i++) {
            UserService::create();
        }

        event(new CommandExecuted(__CLASS__));
    }
}
