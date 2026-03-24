<?php

namespace Tests\Unit\Billing\Application\UseCases\Commands\OpenAccount;

use App\Billing\Application\UseCases\Commands\OpenAccount\OpenAccountCommand;
use App\Billing\Application\UseCases\Commands\OpenAccount\OpenAccountCommandHandler;
use App\Billing\Domain\Account\Account;
use App\Billing\Domain\Account\AccountId;
use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\Events\AccountOpened;
use App\Billing\Domain\Account\Exceptions\AccountAlreadyExistsException;
use App\Billing\Domain\Account\Money;
use App\Billing\Domain\Account\Repositories\AccountRepository;
use App\Billing\Domain\Account\UserId;
use App\Billing\Domain\Policies\DefaultCurrencyPolicy;
use App\Shared\Application\Services\IdGenerator;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class OpenAccountCommandHandlerTest extends TestCase
{
    private IdGenerator $idGeneratorMock;
    private AccountRepository $accountRepositoryMock;
    private DefaultCurrencyPolicy $defaultCurrencyPolicyMock;

    private string $accountId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->idGeneratorMock = Mockery::mock(IdGenerator::class);
        $this->accountRepositoryMock = Mockery::mock(AccountRepository::class);
        $this->defaultCurrencyPolicyMock = Mockery::mock(DefaultCurrencyPolicy::class);

        $this->accountId = Str::uuid()->toString();

        $this->idGeneratorMock
            ->shouldReceive('generate')
            ->once()
            ->andReturn($this->accountId);
    }

    public function testHandleSuccessfully(): void
    {
        $userId = Str::uuid()->toString();

        $this->accountRepositoryMock
            ->shouldReceive('existsByUserId')
            ->once()
            ->with(Mockery::on(function (UserId $id) use ($userId) {
                return $id->equals(UserId::fromString($userId));
            }))
            ->andReturn(false);

        $this->accountRepositoryMock
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function (Account $acc) {
                return $acc->balance()->equals(Money::fromString('1000', Currency::USD()));
            }));

        $this->defaultCurrencyPolicyMock
            ->shouldReceive('determineFor')
            ->once()
            ->with(Mockery::on(function (UserId $id) use ($userId) {
                return $id->equals(UserId::fromString($userId));
            }))
            ->andReturn(Currency::USD());

        $handler = new OpenAccountCommandHandler(
            $this->idGeneratorMock,
            $this->accountRepositoryMock,
            $this->defaultCurrencyPolicyMock
        );

        $result = $handler->handle(new OpenAccountCommand(
            userId: $userId,
            balance: '100000'
        ));

        $events = $result->events();

        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertInstanceOf(AccountOpened::class, $event);
        $this->assertTrue($event->balance->equals(
            Money::fromString('1000', Currency::USD())
        ));
        $this->assertTrue($event->id->equals(
            AccountId::fromString($this->accountId)
        ));
        $this->assertTrue($event->userId->equals(
            UserId::fromString($userId)
        ));
    }

    public function testHandleAlreadyExist(): void
    {
        $userId = Str::uuid()->toString();

        $this->accountRepositoryMock
            ->shouldReceive('existsByUserId')
            ->once()
            ->with(Mockery::on(function (UserId $id) use ($userId) {
                return $id->equals(UserId::fromString($userId));
            }))
            ->andReturn(true);

        $this->accountRepositoryMock
            ->shouldReceive('save')
            ->never();

        $this->expectException(AccountAlreadyExistsException::class);

        $handler = new OpenAccountCommandHandler(
            $this->idGeneratorMock,
            $this->accountRepositoryMock,
            $this->defaultCurrencyPolicyMock
        );

        $handler->handle(new OpenAccountCommand(
            userId: $userId,
            balance: '100000'
        ));
    }
}
