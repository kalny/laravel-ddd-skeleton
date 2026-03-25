<?php

namespace App\Billing\Application\UseCases\Commands\Deposit;

use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\Money;
use App\Billing\Domain\Account\Repositories\AccountRepository;
use App\Billing\Domain\Account\UserId;
use App\Shared\Application\Bus\Command;
use App\Shared\Application\Bus\CommandHandler;
use App\Shared\Application\Bus\CommandResult;

final class DepositCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly AccountRepository $accounts,
    ) {
    }

    public function handle(Command $command): CommandResult
    {
        /** @var DepositCommand $command */

        $deposit = Money::fromString($command->amount, Currency::fromCode($command->currency));

        $account = $this->accounts->getByUserId(UserId::fromString($command->userId));

        $account->credit($deposit);

        $this->accounts->save($account);

        return CommandResult::success(null, $account->releaseEvents());
    }
}
