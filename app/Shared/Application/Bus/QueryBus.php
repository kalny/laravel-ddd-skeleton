<?php

namespace App\Shared\Application\Bus;

interface QueryBus
{
    public function ask(Query $query): mixed;
}
