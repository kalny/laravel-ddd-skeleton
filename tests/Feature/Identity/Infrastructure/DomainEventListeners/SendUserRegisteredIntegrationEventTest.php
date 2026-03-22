<?php

namespace Tests\Feature\Identity\Infrastructure\DomainEventListeners;

use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Events\UserRegistered;
use App\Identity\Domain\User\UserId;
use App\Identity\Infrastructure\DomainEventListeners\UserRegisteredProjector;
use App\Identity\Infrastructure\IntegrationEvents\UserRegisteredIntegrationEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class SendUserRegisteredIntegrationEventTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    public function testHandleSuccessfully(): void
    {
        $projector = app(UserRegisteredProjector::class);

        $projector->handle(new UserRegistered(
            id: UserId::fromString(Str::uuid()->toString()),
            email: Email::fromString('username@gmail.com')
        ));

        Event::assertDispatched(UserRegisteredIntegrationEvent::class);
    }
}
