<?php

namespace Tests\Unit\Identity\Domain\User;

use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Exceptions\InvalidEmailException;
use App\Shared\Domain\Exceptions\InvalidArgumentException;
use Tests\TestCase;

class EmailTest extends TestCase
{
    public function testCreatesValidEmailFromString(): void
    {
        $email = Email::fromString('test@example.com');

        $this->assertSame('test@example.com', $email->getValue());
    }

    public function testNormalizeEmailToLowerCase(): void
    {
        $email = Email::fromString('Test@Example.com');

        $this->assertSame('test@example.com', $email->getValue());
    }

    public function testTrimEmailWhiteSpaces(): void
    {
        $email = Email::fromString('  test@example.com  ');

        $this->assertSame('test@example.com', $email->getValue());
    }

    public function testCreateEmailWithIncorrectFormat(): void
    {
        $this->expectException(InvalidEmailException::class);

        Email::fromString('incorrect');
    }

    public function testCreateEmailFromEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString(' ');
    }

    public function testEqualsTrue(): void
    {
        $email = Email::fromString('test@example.com');
        $sameEmail = Email::fromString('test@example.com');

        $this->assertTrue($email->equals($sameEmail));
    }

    public function testEqualsFalse(): void
    {
        $email = Email::fromString('test@example.com');
        $otherEmail = Email::fromString('other_test@example.com');

        $this->assertFalse($email->equals($otherEmail));
    }
}
