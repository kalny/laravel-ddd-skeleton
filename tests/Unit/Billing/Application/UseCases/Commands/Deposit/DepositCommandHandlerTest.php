<?php

namespace Tests\Unit\Billing\Application\UseCases\Commands\Deposit;

use App\Billing\Application\UseCases\Commands\Deposit\DepositCommand;
use App\Billing\Application\UseCases\Commands\Deposit\DepositCommandHandler;
use App\Billing\Domain\Account\Account;
use App\Billing\Domain\Account\AccountId;
use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\Events\AccountBalanceCredited;
use App\Billing\Domain\Account\Money;
use App\Billing\Domain\Account\Repositories\AccountRepository;
use App\Billing\Domain\Account\UserId;
use Mockery;
use PHPUnit\Framework\TestCase;

class DepositCommandHandlerTest extends TestCase
{
    public function testHandleSuccessfully(): void
    {
        $accountId = 'account-id';
        $userId = 'user-id';

        $account = Account::open(
            id: AccountId::fromString($accountId),
            userId: UserId::fromString($userId),
            currency: Currency::USD(),
        );
        $account->releaseEvents();

        $repositoryMock = Mockery::mock(AccountRepository::class);

        $repositoryMock
            ->shouldReceive('getByUserId')
            ->once()
            ->with(Mockery::on(function (UserId $id) use ($userId) {
                return $id->equals(UserId::fromString($userId));
            }))
            ->andReturn($account);

        $repositoryMock
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function (Account $acc) {
                return $acc->balance()->equals(Money::fromString('1000', Currency::USD()));
            }));

        $handler = new DepositCommandHandler($repositoryMock);

        $result = $handler->handle(new DepositCommand(
            userId: $userId,
            amount: '1000',
            currency: 'USD',
        ));

        $events = $result->events();

        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertInstanceOf(AccountBalanceCredited::class, $event);
        $this->assertTrue($event->amount->equals(
            Money::fromString('1000', Currency::USD())
        ));
    }
}
