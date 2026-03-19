<?php

namespace App\Identity\Domain\Common\Exception;

use App\Shared\Domain\Exception\DomainExceptionBase;

final class InsufficientFundsException extends DomainExceptionBase
{
    public function __construct(string $message = 'Insufficient funds')
    {
        parent::__construct($message);
    }
}
