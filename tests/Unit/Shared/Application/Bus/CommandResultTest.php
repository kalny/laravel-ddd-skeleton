<?php

namespace Tests\Unit\Shared\Application\Bus;

use App\Billing\Domain\Account\Exceptions\InsufficientFundsException;
use App\Shared\Application\Bus\CommandResult;
use PHPUnit\Framework\TestCase;

class CommandResultTest extends TestCase
{
    public function testCreateSuccessResult(): void
    {
        $payload = [
            'id' => 'test-id'
        ];

        $events = [
            'event-1',
            'event-2',
        ];

        $commandResult = CommandResult::success($payload, $events);

        $this->assertTrue($commandResult->isSuccess());
        $this->assertSame($payload, $commandResult->payload());
        $this->assertSame($events, $commandResult->events());
        $this->assertNull($commandResult->error());
    }

    public function testCreateSuccessResultWithoutEvents(): void
    {
        $payload = [
            'id' => 'test-id'
        ];

        $commandResult = CommandResult::success($payload);

        $this->assertTrue($commandResult->isSuccess());
        $this->assertSame($payload, $commandResult->payload());
        $this->assertSame([], $commandResult->events());
        $this->assertNull($commandResult->error());
    }

    public function testCreateSuccessResultWithoutPayload(): void
    {
        $events = [
            'event-1',
            'event-2',
        ];

        $commandResult = CommandResult::success(null, $events);

        $this->assertTrue($commandResult->isSuccess());
        $this->assertSame(null, $commandResult->payload());
        $this->assertSame($events, $commandResult->events());
        $this->assertNull($commandResult->error());
    }

    public function testCreateSuccessResultWithoutEventsAndPayload(): void
    {
        $commandResult = CommandResult::success();

        $this->assertTrue($commandResult->isSuccess());
        $this->assertSame(null, $commandResult->payload());
        $this->assertSame([], $commandResult->events());
        $this->assertNull($commandResult->error());
    }

    public function testCreateFailureResult(): void
    {
        $commandResult = CommandResult::failure(new InsufficientFundsException());

        $this->assertFalse($commandResult->isSuccess());
        $this->assertSame(null, $commandResult->payload());
        $this->assertSame([], $commandResult->events());
        $this->assertNotNull($commandResult->error());
        $this->assertInstanceOf(InsufficientFundsException::class, $commandResult->error());
    }
}
