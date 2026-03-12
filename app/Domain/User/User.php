<?php

namespace App\Domain\User;

use App\Domain\Common\AggregateRoot;
use App\Domain\Common\Email;
use App\Domain\Common\Exceptions\InsufficientFundsException;
use App\Domain\Common\Money;
use App\Domain\User\Events\UserBalanceCredited;
use App\Domain\User\Events\UserBalanceDebited;
use App\Domain\User\Events\UserRegistered;

final class User extends AggregateRoot
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
        $user = new self($id, $name, $email, $password);

        $user->record(new UserRegistered($id, $name, $email));

        return $user;
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

        $this->record(new UserBalanceDebited($this->id, $money, $this->balance));
    }

    public function credit(Money $money): void
    {
        $this->balance = $this->balance->add($money);

        $this->record(new UserBalanceCredited($this->id, $money, $this->balance));
    }
}
