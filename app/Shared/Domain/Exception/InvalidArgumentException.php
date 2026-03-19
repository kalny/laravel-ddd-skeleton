<?php

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\Exception\DomainExceptionBase;

final class InvalidArgumentException extends DomainExceptionBase
{
    public function __construct(string $message = 'Invalid argument')
    {
        parent::__construct($message);
    }
}
