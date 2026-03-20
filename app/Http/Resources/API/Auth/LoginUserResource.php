<?php

namespace App\Http\Resources\API\Auth;

use App\Identity\Application\UseCases\LoginUser\LoginUserResult;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var LoginUserResult $this */
        return [
            'id' => $this->id,
            'email' => $this->email,
            'token' => $this->token,
        ];
    }
}
