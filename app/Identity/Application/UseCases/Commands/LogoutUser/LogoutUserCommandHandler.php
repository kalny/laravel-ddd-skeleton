<?php

namespace App\Identity\Application\UseCases\Commands\LogoutUser;

use App\Shared\Application\Bus\CommandResult;

class LogoutUserCommandHandler
{
    public function handle(LogoutUserCommand $command): CommandResult
    {
        // special logic

        return CommandResult::success();
    }
}
