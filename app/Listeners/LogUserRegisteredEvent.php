<?php

namespace App\Listeners;

use App\Domain\User\Events\UserRegistered;
use Illuminate\Support\Facades\Log;

class LogUserRegisteredEvent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        Log::channel('events')->info('UserRegistered', [
            'user_id' => $event->id->value()
        ]);
    }
}
