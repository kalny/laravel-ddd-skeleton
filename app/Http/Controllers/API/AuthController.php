<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Resources\API\Auth\LoginUserResource;
use App\Http\Resources\API\Auth\RegisterUserResource;
use App\Identity\Application\UseCases\LoginUser\LoginUserHandler;
use App\Identity\Application\UseCases\LogoutUser\LogoutUserHandler;
use App\Identity\Application\UseCases\RegisterUser\RegisterUserHandler;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(
        RegisterRequest $request,
        RegisterUserHandler $handler
    ): RegisterUserResource {
        $result = $handler->handle($request->toCommand());

        return new RegisterUserResource($result);
    }

    public function login(
        LoginRequest $request,
        LoginUserHandler $handler
    ): LoginUserResource {
        $result = $handler->handle($request->toCommand());

        return new LoginUserResource($result);
    }

    public function logout(
        LogoutUserHandler $handler
    ): JsonResponse {
        $handler->handle();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
