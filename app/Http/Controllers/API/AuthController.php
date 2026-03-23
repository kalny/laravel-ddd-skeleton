<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Resources\API\Auth\LoginUserResource;
use App\Http\Resources\API\Auth\RegisterUserResource;
use App\Identity\Application\Services\TokenManager;
use App\Identity\Application\UseCases\Commands\LogoutUser\LogoutUserCommand;
use App\Identity\Application\UseCases\Queries\GetUser\GetUserQuery;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(
        RegisterRequest $request,
        TokenManager $tokenManager,
        CommandBus $commandBus,
        QueryBus $queryBus
    ): RegisterUserResource {
        $userId = $commandBus->dispatch($request->toCommand())->payload();

        $result = $queryBus->ask(new GetUserQuery($userId->value()));

        return (new RegisterUserResource($result))
            ->additional([
                'token' => $tokenManager->create($userId)
            ]);
    }

    public function login(
        LoginRequest $request,
        TokenManager $tokenManager,
        CommandBus $commandBus,
        QueryBus $queryBus
    ): LoginUserResource {
        $userId = $commandBus->dispatch($request->toCommand())->payload();

        $result = $queryBus->ask(new GetUserQuery($userId->value()));

        return (new LoginUserResource($result))
            ->additional([
                'token' => $tokenManager->create($userId)
            ]);
    }

    public function logout(
        TokenManager$tokenManager,
        CommandBus $commandBus,
    ): JsonResponse {
        $commandBus->dispatch(new LogoutUserCommand(Auth::user()->id));

        $tokenManager->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
