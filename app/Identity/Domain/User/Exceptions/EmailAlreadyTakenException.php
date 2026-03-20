<?php

namespace App\Identity\Domain\User\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class EmailAlreadyTakenException extends DomainExceptionBase
{
    public static function withValue(string $value): self
    {
        return new self(sprintf('Email "%s" already taken', $value));
    }
}
