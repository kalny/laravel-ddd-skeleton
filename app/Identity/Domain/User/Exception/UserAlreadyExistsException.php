<?php

namespace App\Identity\Domain\User\Exception;

use App\Shared\Domain\Exception\DomainExceptionBase;

final class UserAlreadyExistsException extends DomainExceptionBase
{
    public static function withValue(string $value): self
    {
        return new self(sprintf('User "%s" already exists', $value));
    }
}
