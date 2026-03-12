<?php

namespace Tests\Feature\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Common\Email;
use App\Domain\Common\Money;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\HashedPassword;
use App\Domain\User\UserId;
use App\Domain\User\UserName;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentUserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\TestCase;

class EloquentUserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentUserRepository $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = app(EloquentUserRepository::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testSuccessfullyGetUser(): void
    {
        $uuid = Str::uuid()->toString();

        $userModel = User::factory()->create([
            'id' => $uuid,
        ]);

        $user = $this->users->get(UserId::fromString($uuid));

        $this->assertTrue($user->getId()->equals(UserId::fromString($userModel->id)));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetNotExistedUser(): void
    {
        $this->expectException(UserNotFoundException::class);
        $uuid = Str::uuid()->toString();

        $this->users->get(UserId::fromString($uuid));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testSuccessfullySaveExistingUser(): void
    {
        $uuid = Str::uuid()->toString();

        User::factory()->create([
            'id' => $uuid,
            'balance' => 1000,
        ]);

        $user = $this->users->get(UserId::fromString($uuid));

        $user->credit(Money::fromInteger(100));

        $this->users->save($user);

        $this->assertDatabaseHas('users', [
            'id' => $uuid,
            'balance' => 1100,
        ]);
    }

    public function testSuccessfullySaveNewUser(): void
    {
        $uuid = Str::uuid()->toString();

        $userId = UserId::fromString($uuid);

        $user = \App\Domain\User\User::register(
            $userId,
            UserName::fromString('username'),
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password_hash'),
        );

        $this->users->save($user);

        $this->assertDatabaseHas('users', [
            'id' => $uuid,
            'balance' => 0,
            'name' => 'username',
            'email' => 'username@test.com',
            'password' => 'password_hash',
        ]);
    }
}
