<?php

namespace App\Identity\Application\UseCases\Commands\LogoutUser;

use App\Shared\Application\Bus\Command;
use App\Shared\Application\Bus\CommandHandler;
use App\Shared\Application\Bus\CommandResult;

final class LogoutUserCommandHandler implements CommandHandler
{
    public function handle(Command $command): CommandResult
    {
        /** @var LogoutUserCommand $command */

        // special logic

        return CommandResult::success();
    }
}
