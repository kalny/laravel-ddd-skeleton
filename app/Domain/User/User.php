<?php

namespace App\Domain\User;

use App\Domain\Common\Email;
use App\Domain\Common\Exceptions\InsufficientFundsException;
use App\Domain\Common\Money;

final class User
{
    private Money $balance;

    private function __construct(
        private readonly UserId $id,
        private readonly UserName $name,
        private readonly Email $email,
        private readonly HashedPassword $password,
    ) {
        $this->balance = Money::zero();
    }

    public static function register(
        UserId $id,
        UserName $name,
        Email $email,
        HashedPassword $password,
    ): self {
        return new self($id, $name, $email, $password);
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function equals(User $other): bool
    {
        return $this->id->equals($other->id);
    }

    public function debit(Money $money): void
    {
        if ($this->balance->lt($money)) {
            throw new InsufficientFundsException('Insufficient funds on the balance');
        }
        $this->balance = $this->balance->subtract($money);
    }

    public function credit(Money $money): void
    {
        $this->balance = $this->balance->add($money);
    }
}
