<?php

namespace Tests\Unit\Identity\Application\UseCases\Commands\LogoutUser;

use App\Identity\Application\UseCases\Commands\LogoutUser\LogoutUserCommand;
use App\Identity\Application\UseCases\Commands\LogoutUser\LogoutUserCommandHandler;
use PHPUnit\Framework\TestCase;

class LogoutUserCommandHandlerTest extends TestCase
{
    public function testHandleSuccessfully(): void
    {
        $userId = 'user-id';

        $handler = new LogoutUserCommandHandler();
        $result = $handler->handle(new LogoutUserCommand(
            id: $userId
        ));

        $this->assertTrue($result->isSuccess());
    }
}
