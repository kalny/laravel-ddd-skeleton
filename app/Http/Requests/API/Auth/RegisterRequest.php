<?php

namespace App\Http\Requests\API\Auth;

use App\Identity\Application\UseCases\RegisterUser\RegisterUserCommand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function toCommand(): RegisterUserCommand
    {
        return new RegisterUserCommand(
            email: $this->validated('email'),
            password: $this->validated('password')
        );
    }
}
