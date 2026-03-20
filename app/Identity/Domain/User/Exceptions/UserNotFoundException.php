<?php

namespace App\Identity\Domain\User\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class UserNotFoundException extends DomainExceptionBase
{
    public function __construct(string $message = 'User not found')
    {
        parent::__construct($message);
    }
}
