<?php

namespace App\Identity\Domain\User;

use App\Identity\Domain\User\Events\UserEmailChanged;
use App\Identity\Domain\User\Events\UserPasswordChanged;
use App\Identity\Domain\User\Events\UserRegistered;
use App\Shared\Domain\AggregateRoot;

final class User extends AggregateRoot
{
    private function __construct(
        private readonly UserId $id,
        private Email $email,
        private HashedPassword $password,
    ) {
    }

    public static function register(
        UserId $id,
        Email $email,
        HashedPassword $password,
    ): self {
        $user = new self($id, $email, $password);

        $user->record(new UserRegistered($id, $email));

        return $user;
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function password(): HashedPassword
    {
        return $this->password;
    }

    public function equals(User $other): bool
    {
        return $this->id->equals($other->id);
    }

    public function hasEmail(Email $email): bool
    {
        return $this->email->equals($email);
    }

    public function changePassword(HashedPassword $newHashedPassword): void
    {
        if ($this->password->equals($newHashedPassword)) {
            return;
        }

        $this->password = $newHashedPassword;
        $this->record(new UserPasswordChanged($this->id));
    }

    public function changeEmail(Email $newEmail): void
    {
        if ($this->email->equals($newEmail)) {
            return;
        }

        $this->email = $newEmail;
        $this->record(new UserEmailChanged($this->id, $this->email));
    }
}
