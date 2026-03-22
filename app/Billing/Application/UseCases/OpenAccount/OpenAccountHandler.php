<?php

namespace App\Billing\Application\UseCases\OpenAccount;

use App\Billing\Domain\Account\Account;
use App\Billing\Domain\Account\AccountId;
use App\Billing\Domain\Account\Exceptions\AccountAlreadyExistsException;
use App\Billing\Domain\Account\Money;
use App\Billing\Domain\Account\Repositories\AccountRepository;
use App\Billing\Domain\Account\UserId;
use App\Billing\Domain\Policies\DefaultCurrencyPolicy;
use App\Shared\Application\Services\EventDispatcher;
use App\Shared\Application\Services\IdGenerator;
use App\Shared\Application\Services\TransactionManager;

final class OpenAccountHandler implements OpenAccount
{
    public function __construct(
        private readonly IdGenerator $idGenerator,
        private readonly TransactionManager $transactionManager,
        private readonly EventDispatcher $dispatcher,
        private readonly AccountRepository $accounts,
        private readonly DefaultCurrencyPolicy $currencyPolicy
    ) {
    }

    public function handle(OpenAccountCommand $command): OpenAccountResult
    {
        $id = $this->idGenerator->generate();
        $userId = $command->userId;
        $balance = $command->balance;

        if ($this->accounts->existsByUserId(UserId::fromString($userId))) {
            throw AccountAlreadyExistsException::withUuid($userId);
        }

        $currency = $this->currencyPolicy->determineFor(UserId::fromString($userId));

        $account = Account::openWithBalance(
            AccountId::fromString($id),
            UserId::fromString($userId),
            Money::fromMinor($balance, $currency)
        );

        return $this->transactionManager->transactional(function () use ($account, $id, $userId, $balance) {
            $this->accounts->save($account);

            foreach ($account->releaseEvents() as $event) {
                $this->dispatcher->dispatch($event);
            }

            return new OpenAccountResult($id, $userId, $balance);
        });
    }
}
