<?php

namespace Tests\Unit\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\Query;
use App\Shared\Infrastructure\Bus\LaravelQueryBus;
use Illuminate\Contracts\Container\Container;
use Mockery;
use PHPUnit\Framework\TestCase;

class LaravelQueryBusTest extends TestCase
{
    public function testAskSuccessfully(): void
    {
        $containerMock = Mockery::mock(Container::class);

        $containerMock
            ->shouldReceive('make')
            ->once()
            ->andReturn(new TestQueryHandler());

        $laravelQueryBus = new LaravelQueryBus($containerMock);
        $laravelQueryBus->ask(new TestQuery());

        $this->assertTrue(true); // this test only check $containerMock->make() fact
    }
}

class TestQuery implements Query {}
class TestQueryHandler {
    public function handle(TestQuery $query): array
    {
        return [];
    }
}
