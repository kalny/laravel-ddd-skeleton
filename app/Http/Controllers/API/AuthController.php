<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Resources\API\Auth\LoginUserResource;
use App\Http\Resources\API\Auth\RegisterUserResource;
use App\Identity\Application\Services\TokenManager;
use App\Identity\Application\UseCases\LoginUser\LoginUserHandler;
use App\Identity\Application\UseCases\LogoutUser\LogoutUserCommand;
use App\Identity\Application\UseCases\LogoutUser\LogoutUserHandler;
use App\Identity\Application\UseCases\RegisterUser\RegisterUserHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(
        RegisterRequest $request,
        RegisterUserHandler $handler,
        TokenManager $tokenManager,
    ): RegisterUserResource {
        $result = $handler->handle($request->toCommand());

        return (new RegisterUserResource($result))
            ->additional([
                'token' => $tokenManager->create($result->id)
            ]);
    }

    public function login(
        LoginRequest $request,
        LoginUserHandler $handler,
        TokenManager $tokenManager,
    ): LoginUserResource {
        $result = $handler->handle($request->toCommand());

        return (new LoginUserResource($result))
            ->additional([
                'token' => $tokenManager->create($result->id)
            ]);
    }

    public function logout(
        LogoutUserHandler $handler,
        TokenManager $tokenManager,
    ): JsonResponse {
        $handler->handle(new LogoutUserCommand(
            id: Auth::user()->id
        ));

        $tokenManager->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
