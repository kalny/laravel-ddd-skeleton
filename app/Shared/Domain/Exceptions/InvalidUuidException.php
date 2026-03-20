<?php

namespace App\Shared\Domain\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class InvalidUuidException extends DomainExceptionBase
{
    public static function withUuid(string $uuid): self
    {
        return new self(sprintf('Invalid UUID "%s"', $uuid));
    }
}
