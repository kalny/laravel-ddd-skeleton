<?php

namespace App\Domain\Common\Exceptions;

final class InvalidUuidException extends DomainExceptionBase
{
    public static function withUuid(string $uuid): self
    {
        return new self(sprintf('Invalid UUID "%s"', $uuid));
    }
}
