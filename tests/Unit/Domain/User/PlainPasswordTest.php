<?php

namespace Tests\Unit\Domain\User;

use App\Domain\Common\Exceptions\InvalidArgumentException;
use App\Domain\User\PlainPassword;
use Tests\TestCase;

class PlainPasswordTest extends TestCase
{
    public function testCreatesValidPlainPasswordFromHash(): void
    {
        $password = PlainPassword::fromString('password');

        $this->assertSame('password', $password->getValue());
    }

    public function testTrimPlainPasswordWhiteSpaces(): void
    {
        $userName = PlainPassword::fromString('  password  ');

        $this->assertSame('password', $userName->getValue());
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
