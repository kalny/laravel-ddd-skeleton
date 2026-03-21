<?php

namespace Tests\Unit\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidUuidException;
use App\Shared\Domain\ValueObjects\UuidId;
use Illuminate\Support\Str;
use Tests\TestCase;

class UuidIdTest extends TestCase
{
    public function testSuccessfullyCreateUuidIdFromString(): void
    {
        $uuid = Str::uuid()->toString();

        $uuidId = new UuidId($uuid);

        $this->assertSame($uuid, $uuidId->value());
    }

    public function testCreateUuidIdFromInvalidString(): void
    {
        $this->expectException(InvalidUuidException::class);

        new UuidId('wrong-uuid');
    }
}
