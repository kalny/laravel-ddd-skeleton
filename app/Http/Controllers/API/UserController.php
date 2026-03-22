<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\ChangeUserEmailRequest;
use App\Http\Requests\API\User\ChangeUserPasswordRequest;
use App\Shared\Application\Bus\CommandBus;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function changePassword(
        string $id,
        ChangeUserPasswordRequest $request,
        CommandBus $commandBus
    ): JsonResponse {
        $commandBus->dispatch($request->toCommand($id));

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function changeEmail(
        string $id,
        ChangeUserEmailRequest $request,
        CommandBus $commandBus
    ): JsonResponse {
        $commandBus->dispatch($request->toCommand($id));

        return response()->json([
            'status' => 'success',
        ]);
    }
}
