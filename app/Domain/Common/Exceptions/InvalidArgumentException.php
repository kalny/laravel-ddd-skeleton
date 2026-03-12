<?php

namespace App\Domain\Common\Exceptions;

final class InvalidArgumentException extends DomainExceptionBase
{
    public function __construct(string $message = 'Invalid argument')
    {
        parent::__construct($message);
    }
}
