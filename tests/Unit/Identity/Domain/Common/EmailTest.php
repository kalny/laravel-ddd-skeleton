<?php

namespace Tests\Unit\Identity\Domain\Common;

use App\Identity\Domain\Common\Email;
use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Identity\Domain\Common\Exception\InvalidEmailException;
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
}
