<?php

namespace App\Http\Requests\API\User;

use App\Application\UseCase\ChangeUserPassword\ChangeUserPasswordCommand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangeUserPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function toCommand(string $id): ChangeUserPasswordCommand
    {
        return new ChangeUserPasswordCommand(
            id: $id,
            password: $this->validated('password')
        );
    }
}
