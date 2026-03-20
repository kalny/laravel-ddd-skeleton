<?php

namespace App\Identity\Domain\User\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class InvalidEmailException extends DomainExceptionBase
{
    public static function withValue(string $value): self
    {
        return new self(sprintf('Invalid email "%s"', $value));
    }
}
