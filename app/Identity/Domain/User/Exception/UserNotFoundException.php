<?php

namespace App\Identity\Domain\User\Exception;

use App\Shared\Domain\Exception\DomainExceptionBase;

final class UserNotFoundException extends DomainExceptionBase
{
    public function __construct(string $message = 'User not found')
    {
        parent::__construct($message);
    }
}
