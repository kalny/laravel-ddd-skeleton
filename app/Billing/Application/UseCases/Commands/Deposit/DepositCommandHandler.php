<?php

namespace App\Billing\Application\UseCases\Commands\Deposit;

use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\Money;
use App\Billing\Domain\Account\Repositories\AccountRepository;
use App\Billing\Domain\Account\UserId;
use App\Shared\Application\Bus\CommandResult;
use App\Shared\Application\Bus\EventBus;
use App\Shared\Application\Services\TransactionManager;

final class DepositCommandHandler
{
    public function __construct(
        private readonly TransactionManager $transactionManager,
        private readonly EventBus $eventBus,
        private readonly AccountRepository $accounts,
    ) {
    }

    public function handle(DepositCommand $command): CommandResult
    {
        $deposit = Money::fromString($command->amount, Currency::fromCode($command->currency));

        $account = $this->accounts->getByUserId(UserId::fromString($command->userId));

        $account->credit($deposit);

        $this->transactionManager->transactional(function () use ($account) {
            $this->accounts->save($account);

            foreach ($account->releaseEvents() as $event) {
                $this->eventBus->dispatch($event);
            }
        });

        return CommandResult::success();
    }
}
