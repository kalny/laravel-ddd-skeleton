<?php

namespace App\Identity\Infrastructure\Listeners;

use App\Identity\Domain\User\Event\UserBalanceCredited;
use Illuminate\Support\Facades\Log;

class LogUserBalanceCreditedEvent
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
    public function handle(UserBalanceCredited $event): void
    {
        Log::channel('events')->info('UserBalanceCredited', [
            'user_id' => $event->id->value()
        ]);
    }
}
