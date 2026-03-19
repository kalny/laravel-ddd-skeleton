<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Resources\API\Auth\RegisterUserResource;
use App\Identity\Application\UseCase\RegisterUser\RegisterUserHandler;

class AuthController extends Controller
{
    public function register(
        RegisterRequest $request,
        RegisterUserHandler $handler
    ): RegisterUserResource {
        $result = $handler->handle($request->toCommand());

        return new RegisterUserResource($result);
    }
}
