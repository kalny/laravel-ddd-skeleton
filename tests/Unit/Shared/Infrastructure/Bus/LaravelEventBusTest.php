<?php

namespace Tests\Unit\Shared\Infrastructure\Bus;

use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Events\UserRegistered;
use App\Identity\Domain\User\UserId;
use App\Shared\Infrastructure\Bus\LaravelEventBus;
use Illuminate\Contracts\Events\Dispatcher;
use Mockery;
use PHPUnit\Framework\TestCase;

class LaravelEventBusTest extends TestCase
{
    public function testDispatchSuccessfully(): void
    {
        $dispatcherMock = Mockery::mock(Dispatcher::class);

        $dispatcherMock
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::on(function (UserRegistered $event) {
                return $event->id->equals(UserId::fromString('user-id'))
                    && $event->email->equals(Email::fromString('username@gmail.com'));
            }));

        $laravelEventBus = new LaravelEventBus($dispatcherMock);
        $laravelEventBus->dispatch(new UserRegistered(
            id: UserId::fromString('user-id'),
            email: Email::fromString('username@gmail.com')
        ));

        $this->assertTrue(true); // this test only check $dispatcherMock->dispatch() fact
    }
}
