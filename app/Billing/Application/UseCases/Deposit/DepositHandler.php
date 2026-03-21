<?php

namespace App\Billing\Application\UseCases\Deposit;

use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\Money;
use App\Billing\Domain\Account\Repositories\AccountRepository;
use App\Billing\Domain\Account\UserId;
use App\Shared\Application\Services\EventDispatcher;
use App\Shared\Application\Services\TransactionManager;

final class DepositHandler
{
    public function __construct(
        private readonly TransactionManager $transactionManager,
        private readonly EventDispatcher $dispatcher,
        private readonly AccountRepository $accounts,
    ) {
    }

    public function handle(DepositCommand $command): void
    {
        $deposit = Money::fromString($command->amount, Currency::fromCode($command->currency));

        $account = $this->accounts->getByUserId(UserId::fromString($command->userId));

        $account->credit($deposit);

        $this->transactionManager->transactional(function () use ($account) {
            $this->accounts->save($account);

            foreach ($account->releaseEvents() as $event) {
                $this->dispatcher->dispatch($event);
            }
        });
    }
}
