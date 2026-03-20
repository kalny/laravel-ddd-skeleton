<?php

namespace App\Billing\Infrastructure\DomainEventListener;

use App\Billing\Domain\Account\Events\AccountBalanceCredited;
use Illuminate\Support\Facades\Log;

class LogAccountBalanceCreditedEvent
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
    public function handle(AccountBalanceCredited $event): void
    {
        Log::channel('events')->info('AccountBalanceCredited', [
            'account_id' => $event->id->value()
        ]);
    }
}
