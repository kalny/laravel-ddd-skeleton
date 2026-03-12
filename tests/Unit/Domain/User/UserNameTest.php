<?php

namespace Tests\Unit\Domain\User;

use App\Domain\Common\Exceptions\InvalidArgumentException;
use App\Domain\User\UserName;
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
}
