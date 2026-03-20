<?php

namespace Tests\Feature\Identity\Infrastructure\Services;

use App\Identity\Domain\User\UserId;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use App\Identity\Infrastructure\Services\LaravelTokenManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class LaravelTokenManagerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate(): void
    {
        $userModel = User::factory()->create();

        $manager = app(LaravelTokenManager::class);

        $token = $manager->create(UserId::fromString($userModel->id));

        [$id, $plainToken] = explode('|', $token);
        $model = PersonalAccessToken::find($id);

        $this->assertTrue(
            hash_equals($model->token, hash('sha256', $plainToken))
        );
    }
}
