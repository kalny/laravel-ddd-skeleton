<?php

namespace App\Infrastructure\Listeners;

use App\Domain\User\Events\UserPasswordChanged;
use Illuminate\Support\Facades\Log;

class LogUserPasswordChangedEvent
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
    public function handle(UserPasswordChanged $event): void
    {
        Log::channel('events')->info('UserPasswordChanged', [
            'user_id' => $event->id->value()
        ]);
    }
}
