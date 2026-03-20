<?php

namespace App\Identity\Domain\User\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class InvalidCredentialsException extends DomainExceptionBase
{
    public function __construct(string $message = 'Invalid credentials')
    {
        parent::__construct($message);
    }
}
