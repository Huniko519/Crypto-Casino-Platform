<?php

namespace App\Listeners;

use App\Events\CommandExecuted;
use App\Helpers\CommandManager;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogExecutedCommand
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommandExecuted  $event
     * @return void
     */
    public function handle(CommandExecuted $event)
    {
        CommandManager::log($event->class);
    }
}
