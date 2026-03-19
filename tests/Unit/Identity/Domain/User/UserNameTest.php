<?php

namespace Tests\Unit\Identity\Domain\User;

use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Identity\Domain\User\UserName;
use Tests\TestCase;

class UserNameTest extends TestCase
{
    public function testCreatesValidUserNameFromString(): void
    {
        $userName = UserName::fromString('test_user');

        $this->assertSame('test_user', $userName->getValue());
    }

    public function testNormalizeUserNameToLowerCase(): void
    {
        $userName = UserName::fromString('test_user');

        $this->assertSame('test_user', $userName->getValue());
    }

    public function testTrimUserNameWhiteSpaces(): void
    {
        $userName = UserName::fromString('  test_user  ');

        $this->assertSame('test_user', $userName->getValue());
    }

    public function testCreateUserNameFromEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        UserName::fromString(' ');
    }

    public function testEqualsTrue(): void
    {
        $userName = UserName::fromString('test_user');
        $sameUserName = UserName::fromString('test_user');

        $this->assertTrue($userName->equals($sameUserName));
    }

    public function testEqualsFalse(): void
    {
        $userName = UserName::fromString('test_user');
        $otherUserName = UserName::fromString('other_test_user');

        $this->assertFalse($userName->equals($otherUserName));
    }
}
