<?php

namespace App\Console\Commands;

use App\Events\CommandExecuted;
use App\Models\Game;
use App\Models\User;
use Illuminate\Console\Command;

class DeleteGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:games {count=100} {user=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete games';

    protected $comments = 'Set count to 0 if you want to delete absolutely all games. Input user ID if you like to delete games of specific user.';

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
        $count = intval($this->argument('count'));
        $userId = intval($this->argument('user'));

        Game::orderBy('id', 'asc')
            ->when($count > 0, function($query) use($count) {
                $query->limit($count);
            })
            ->when($userId > 0, function ($query) use ($userId) {
                $query->where('account_id', User::find($userId)->account->id);
            })
            ->get()
            // it's important to load each model one by one,
            // otherwise model's delete() method would not be fired and polymorphic relations would not be deleted.
            ->each(function ($game) {
                $game->delete();
            });

        event(new CommandExecuted(__CLASS__));
    }
}
