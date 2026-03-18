<?php

namespace App\Listeners;

use App\Domain\User\Events\UserNameChanged;
use Illuminate\Support\Facades\Log;

class LogUserNameChangedEvent
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
    public function handle(UserNameChanged $event): void
    {
        Log::channel('events')->info('UserNameChanged', [
            'user_id' => $event->id->value()
        ]);
    }
}
