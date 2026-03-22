<?php

namespace App\Http\Requests\API\Auth;

use App\Identity\Application\UseCases\Commands\LoginUser\LoginUserCommand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function toCommand(): LoginUserCommand
    {
        return new LoginUserCommand(
            email: $this->validated('email'),
            password: $this->validated('password')
        );
    }
}
