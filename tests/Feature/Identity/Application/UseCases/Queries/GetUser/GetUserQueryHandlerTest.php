<?php

namespace Tests\Feature\Identity\Application\UseCases\Queries\GetUser;

use App\Identity\Application\DTO\UserDTO;
use App\Identity\Application\UseCases\Queries\GetUser\GetUserQuery;
use App\Identity\Application\UseCases\Queries\GetUser\GetUserQueryHandler;
use App\Identity\Domain\User\Exceptions\UserNotFoundException;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class GetUserQueryHandlerTest extends TestCase
{
    use DatabaseMigrations;

    private GetUserQueryHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->handler = app(GetUserQueryHandler::class);
    }

    public function testHandleSuccessfully(): void
    {
        $userModel = User::factory()->create();

        $result = $this->handler->handle(new GetUserQuery(
            id: $userModel->id,
        ));

        $this->assertEquals(new UserDTO(
            id: $userModel->id,
            email: $userModel->email,
        ), $result);
    }

    public function testHandleUserNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->handler->handle(new GetUserQuery(
            id: 'wrong-id',
        ));
    }
}
