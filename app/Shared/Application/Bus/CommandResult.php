<?php

namespace App\Shared\Application\Bus;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final readonly class CommandResult
{
    private function __construct(
        private bool $success,
        private mixed $payload = null,
        private array $events = [],
        private ?DomainExceptionBase $error = null
    ) {
    }

    public static function success(mixed $payload = null, array $events = []): self
    {
        return new self(true, $payload, $events);
    }

    public static function failure(DomainExceptionBase $error): self
    {
        return new self(false, null, [], $error);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function payload(): mixed
    {
        return $this->payload;
    }

    public function events(): array
    {
        return $this->events;
    }

    public function error(): ?DomainExceptionBase
    {
        return $this->error;
    }
}
