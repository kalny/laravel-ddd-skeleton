<?php

namespace App\Identity\Infrastructure\Listeners;

use App\Identity\Domain\User\Event\UserBalanceDebited;
use Illuminate\Support\Facades\Log;

class LogUserBalanceDebitedEvent
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
    public function handle(UserBalanceDebited $event): void
    {
        Log::channel('events')->info('UserBalanceDebited', [
            'user_id' => $event->id->value()
        ]);
    }
}
