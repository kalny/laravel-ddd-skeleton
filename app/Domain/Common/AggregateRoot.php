<?php

namespace App\Domain\Common;

abstract class AggregateRoot
{
    private array $events = [];

    protected function record(object $event): void
    {
        $this->events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
