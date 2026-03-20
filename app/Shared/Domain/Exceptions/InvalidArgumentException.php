<?php

namespace App\Shared\Domain\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class InvalidArgumentException extends DomainExceptionBase
{
    public function __construct(string $message = 'Invalid argument')
    {
        parent::__construct($message);
    }
}
