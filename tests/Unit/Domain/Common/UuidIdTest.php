<?php

namespace Tests\Unit\Domain\Common;

use App\Domain\Common\Exceptions\InvalidUuidException;
use App\Domain\Common\UuidId;
use Illuminate\Support\Str;
use Tests\TestCase;

class UuidIdTest extends TestCase
{
    public function testSuccessfullyCreateUuidIdFromString(): void
    {
        $uuid = Str::uuid()->toString();

        $uuidId = new UuidId($uuid);

        $this->assertSame($uuid, $uuidId->getValue());
    }

    public function testCreateUuidIdFromInvalidString(): void
    {
        $this->expectException(InvalidUuidException::class);

        new UuidId('wrong-uuid');
    }
}
