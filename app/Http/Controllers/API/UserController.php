<?php

namespace App\Http\Controllers\API;

use App\Application\UseCase\ChangeUserName\ChangeUserNameHandler;
use App\Application\UseCase\ChangeUserPassword\ChangeUserPasswordHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\ChangeUserNameRequest;
use App\Http\Requests\API\User\ChangeUserPasswordRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function changeName(
        string $id,
        ChangeUserNameRequest $request,
        ChangeUserNameHandler $handler
    ): JsonResponse {
        $command = $request->toCommand($id);
        $handler->handle($command);

        return response()->json(['OK']);
    }

    public function changePassword(
        string $id,
        ChangeUserPasswordRequest $request,
        ChangeUserPasswordHandler $handler
    ): JsonResponse {
        $command = $request->toCommand($id);
        $handler->handle($command);

        return response()->json(['OK']);
    }
}
