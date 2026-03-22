<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Billing\DepositRequest;
use App\Shared\Application\Bus\CommandBus;
use Illuminate\Http\JsonResponse;

class BillingController extends Controller
{
    public function deposit(
        string $id,
        DepositRequest $request,
        CommandBus $commandBus
    ): JsonResponse {
        $commandBus->dispatch($request->toCommand($id));

        return response()->json([
            'status' => 'success',
        ]);
    }
}
