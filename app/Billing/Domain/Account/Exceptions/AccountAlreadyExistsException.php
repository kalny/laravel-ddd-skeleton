<?php

namespace App\Billing\Domain\Account\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class AccountAlreadyExistsException extends DomainExceptionBase
{
    public static function withUuid(string $uuid): self
    {
        return new self(sprintf('Account for user "%s" already created', $uuid));
    }
}
