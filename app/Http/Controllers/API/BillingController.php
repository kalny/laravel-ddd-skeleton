<?php

namespace App\Http\Controllers\API;

use App\Billing\Application\UseCases\Deposit\DepositHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Billing\DepositRequest;
use Illuminate\Http\JsonResponse;

class BillingController extends Controller
{
    public function deposit(
        string $id,
        DepositRequest $request,
        DepositHandler $handler
    ): JsonResponse {
        $command = $request->toCommand($id);
        $handler->handle($command);

        return response()->json([
            'status' => 'success',
        ]);
    }
}
