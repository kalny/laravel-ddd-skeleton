<?php

namespace App\Billing\Domain\Account\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class NegativeMultiplierException extends DomainExceptionBase
{
    public static function withValue(int $value): self
    {
        return new self(sprintf('Negative multiplier: %s', $value));
    }
}
