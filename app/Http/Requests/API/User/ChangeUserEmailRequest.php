<?php

namespace App\Http\Requests\API\User;

use App\Identity\Application\UseCases\ChangeUserEmail\ChangeUserEmailCommand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangeUserEmailRequest extends FormRequest
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
        ];
    }

    public function toCommand(string $id): ChangeUserEmailCommand
    {
        return new ChangeUserEmailCommand(
            id: $id,
            email: $this->validated('email')
        );
    }
}
