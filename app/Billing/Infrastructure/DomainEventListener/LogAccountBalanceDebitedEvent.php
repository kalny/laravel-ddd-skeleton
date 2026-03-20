<?php

namespace App\Billing\Infrastructure\DomainEventListener;

use App\Billing\Domain\Account\Events\AccountBalanceDebited;
use Illuminate\Support\Facades\Log;

class LogAccountBalanceDebitedEvent
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
    public function handle(AccountBalanceDebited $event): void
    {
        Log::channel('events')->info('AccountBalanceDebited', [
            'user_id' => $event->id->value()
        ]);
    }
}
