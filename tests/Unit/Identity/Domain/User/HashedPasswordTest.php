<?php

namespace Tests\Unit\Identity\Domain\User;

use App\Shared\Domain\Exceptions\InvalidArgumentException;
use App\Identity\Domain\User\HashedPassword;
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
        $password = HashedPassword::fromHash('  password  ');

        $this->assertSame('password', $password->getValue());
    }

    public function testCreateHashedPasswordFromEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        HashedPassword::fromHash(' ');
    }

    public function testEqualsTrue(): void
    {
        $password = HashedPassword::fromHash('hash1');
        $samePassword = HashedPassword::fromHash('hash1');

        $this->assertTrue($password->equals($samePassword));
    }

    public function testEqualsFalse(): void
    {
        $password = HashedPassword::fromHash('hash1');
        $otherPassword = HashedPassword::fromHash('hash2');

        $this->assertFalse($password->equals($otherPassword));
    }
}
