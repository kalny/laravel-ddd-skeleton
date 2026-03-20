<?php

namespace App\Billing\Application\UseCases\OpenAccount;

use App\Billing\Domain\Account\Account;
use App\Billing\Domain\Account\AccountId;
use App\Billing\Domain\Account\Exceptions\AccountAlreadyExistsException;
use App\Billing\Domain\Account\Money;
use App\Billing\Domain\Account\Repositories\AccountRepository;
use App\Billing\Domain\Account\UserId;
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
    ) {
    }

    public function handle(OpenAccountCommand $command): OpenAccountResult
    {
        $userId = $command->userId;
        $balance = $command->balance;

        if ($this->accounts->existsByUserId(UserId::fromString($userId))) {
            throw AccountAlreadyExistsException::withUuid($userId);
        }

        $id = $this->idGenerator->generate();

        $account = Account::openWithBalance(
            AccountId::fromString($id),
            UserId::fromString($userId),
            Money::fromInteger($balance)
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
