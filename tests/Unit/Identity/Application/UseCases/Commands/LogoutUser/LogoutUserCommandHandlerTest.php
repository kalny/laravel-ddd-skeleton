<?php

namespace Tests\Unit\Identity\Application\UseCases\Commands\LogoutUser;

use App\Identity\Application\UseCases\Commands\LogoutUser\LogoutUserCommand;
use App\Identity\Application\UseCases\Commands\LogoutUser\LogoutUserCommandHandler;
use Illuminate\Support\Str;
use Tests\TestCase;

class LogoutUserCommandHandlerTest extends TestCase
{
    public function testHandleSuccessfully(): void
    {
        $userId = Str::uuid()->toString();

        $handler = new LogoutUserCommandHandler();
        $result = $handler->handle(new LogoutUserCommand(
            id: $userId
        ));

        $this->assertTrue($result->isSuccess());
    }
}
