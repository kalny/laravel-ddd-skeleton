<?php

namespace Tests\Unit\Domain\User;

use App\Domain\Common\Exceptions\InvalidArgumentException;
use App\Domain\User\HashedPassword;
use Tests\TestCase;

class HashedPasswordTest extends TestCase
{
    public function testCreatesValidHashedPasswordFromHash(): void
    {
        $password = HashedPassword::fromHash('password');

        $this->assertSame('password', $password->getValue());
    }

    public function testTrimHashedPasswordWhiteSpaces(): void
    {
        $userName = HashedPassword::fromHash('  password  ');

        $this->assertSame('password', $userName->getValue());
    }

    public function testCreateHashedPasswordFromEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        HashedPassword::fromHash(' ');
    }
}
