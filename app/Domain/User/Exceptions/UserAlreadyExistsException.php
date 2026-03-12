<?php

namespace App\Domain\User\Exceptions;

use App\Domain\Common\Exceptions\DomainExceptionBase;

final class UserAlreadyExistsException extends DomainExceptionBase
{
    public static function withValue(string $value): self
    {
        return new self(sprintf('User "%s" already exists', $value));
    }
}
