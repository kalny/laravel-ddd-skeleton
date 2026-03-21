<?php

namespace Tests\Unit\Identity\Domain\User;

use App\Shared\Domain\Exceptions\InvalidArgumentException;
use App\Identity\Domain\User\PlainPassword;
use Tests\TestCase;

class PlainPasswordTest extends TestCase
{
    public function testCreatesValidPlainPasswordFromHash(): void
    {
        $password = PlainPassword::fromString('password');

        $this->assertSame('password', $password->value());
    }

    public function testTrimPlainPasswordWhiteSpaces(): void
    {
        $userName = PlainPassword::fromString('  password  ');

        $this->assertSame('password', $userName->value());
    }

    public function testCreatePlainPasswordFromEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        PlainPassword::fromString(' ');
    }

    public function testCreatePlainPasswordFromShortString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        PlainPassword::fromString('pass');
    }
}
