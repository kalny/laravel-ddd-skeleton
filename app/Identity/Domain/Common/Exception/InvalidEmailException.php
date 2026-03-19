<?php

namespace App\Identity\Domain\Common\Exception;

use App\Shared\Domain\Exception\DomainExceptionBase;

final class InvalidEmailException extends DomainExceptionBase
{
    public static function withValue(string $value): self
    {
        return new self(sprintf('Invalid email "%s"', $value));
    }
}
