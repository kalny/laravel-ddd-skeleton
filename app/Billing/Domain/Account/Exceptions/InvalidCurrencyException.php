<?php

namespace App\Billing\Domain\Account\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class InvalidCurrencyException extends DomainExceptionBase
{
    public static function withValue(string $value): self
    {
        return new self(sprintf('Currency "%s" not supported', $value));
    }
}
