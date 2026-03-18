<?php

namespace App\Domain\User;

use App\Domain\Common\AggregateRoot;
use App\Domain\Common\Email;
use App\Domain\Common\Exceptions\InsufficientFundsException;
use App\Domain\Common\Money;
use App\Domain\User\Events\UserBalanceCredited;
use App\Domain\User\Events\UserBalanceDebited;
use App\Domain\User\Events\UserNameChanged;
use App\Domain\User\Events\UserPasswordChanged;
use App\Domain\User\Events\UserRegistered;

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
