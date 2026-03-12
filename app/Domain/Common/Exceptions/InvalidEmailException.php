<?php

namespace App\Domain\Common\Exceptions;

final class InvalidEmailException extends DomainExceptionBase
{
    public static function withValue(string $value): self
    {
        return new self(sprintf('Invalid email "%s"', $value));
    }
}
