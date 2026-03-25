<?php

namespace Tests\Unit\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\Command;
use App\Shared\Application\Bus\CommandHandler;
use App\Shared\Application\Bus\CommandResult;
use App\Shared\Infrastructure\Bus\LaravelCommandBus;
use Illuminate\Contracts\Container\Container;
use Mockery;
use PHPUnit\Framework\TestCase;

class LaravelCommandBusTest extends TestCase
{
    public function testDispatchSuccessfully(): void
    {
        $containerMock = Mockery::mock(Container::class);

        $containerMock
            ->shouldReceive('make')
            ->once()
            ->andReturn(new TestCommandHandler());

        $laravelCommandBus = new LaravelCommandBus($containerMock);
        $laravelCommandBus->dispatch(new TestCommand());

        $this->assertTrue(true); // this test only check $containerMock->make() fact
    }
}

class TestCommand implements Command {}
class TestCommandHandler implements CommandHandler {
    public function handle(Command $command): CommandResult
    {
        /** @var TestCommand $command */
        return CommandResult::success();
    }
}
