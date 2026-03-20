<?php

namespace App\Billing\Infrastructure\DomainEventListener;

use App\Billing\Domain\Account\Events\AccountOpened;
use Illuminate\Support\Facades\Log;

class LogAccountOpenedEvent
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
    public function handle(AccountOpened $event): void
    {
        Log::channel('events')->info('AccountOpened', [
            'account_id' => $event->id->value()
        ]);
    }
}
