<?php

namespace App\Domain\Common\Exceptions;

final class InsufficientFundsException extends DomainExceptionBase
{
    public function __construct(string $message = 'Insufficient funds')
    {
        parent::__construct($message);
    }
}
