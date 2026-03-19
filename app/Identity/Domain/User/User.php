<?php

namespace App\Identity\Domain\User;

use App\Shared\Domain\AggregateRoot;
use App\Identity\Domain\Common\Email;
use App\Identity\Domain\Common\Exception\InsufficientFundsException;
use App\Identity\Domain\Common\Money;
use App\Identity\Domain\User\Event\UserBalanceCredited;
use App\Identity\Domain\User\Event\UserBalanceDebited;
use App\Identity\Domain\User\Event\UserNameChanged;
use App\Identity\Domain\User\Event\UserPasswordChanged;
use App\Identity\Domain\User\Event\UserRegistered;

final class User extends AggregateRoot
{
    private Money $balance;

    private function __construct(
        private readonly UserId $id,
        private UserName $name,
        private readonly Email $email,
        private HashedPassword $password,
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

    public function id(): UserId
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

    public function changeName(UserName $newName): void
    {
        if ($this->name->equals($newName)) {
            return;
        }

        $this->name = $newName;
        $this->record(new UserNameChanged($this->id, $newName));
    }

    public function changePassword(HashedPassword $newHashedPassword): void
    {
        if ($this->password->equals($newHashedPassword)) {
            return;
        }

        $this->password = $newHashedPassword;
        $this->record(new UserPasswordChanged($this->id));
    }
}
