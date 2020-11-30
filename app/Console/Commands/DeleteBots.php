<?php

namespace App\Console\Commands;

use App\Events\CommandExecuted;
use App\Models\User;
use Illuminate\Console\Command;

class DeleteBots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:bots {count=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete bots';

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
        User::where('role', User::ROLE_BOT)
            ->orderBy('id','desc')
            ->limit(intval($this->argument('count')))
            ->get()
            // it's important to load each model one by one,
            // otherwise model's delete() method would not be fired and polymorphic relations would not be deleted.
            ->each(function ($user) {
                $user->delete();
            });

        event(new CommandExecuted(__CLASS__));
    }
}
