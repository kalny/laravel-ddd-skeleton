<?php

namespace App\Identity\Domain\User\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class UserAlreadyExistsException extends DomainExceptionBase
{
    public static function withValue(string $value): self
    {
        return new self(sprintf('User "%s" already exists', $value));
    }
}
