<?php

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\Exception\DomainExceptionBase;

final class InvalidUuidException extends DomainExceptionBase
{
    public static function withUuid(string $uuid): self
    {
        return new self(sprintf('Invalid UUID "%s"', $uuid));
    }
}
