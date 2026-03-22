<?php

namespace App\Http\Requests\API\Billing;

use App\Billing\Application\UseCases\Commands\Deposit\DepositCommand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepositRequest extends FormRequest
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
            'amount' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d+(\.\d{1,8})?$/',
            ],

            'currency' => [
                'required',
                Rule::in(['USD', 'EUR', 'UAH']),
            ],
        ];
    }

    public function toCommand(string $id): DepositCommand
    {
        return new DepositCommand(
            userId: $id,
            amount: $this->validated('amount'),
            currency: $this->validated('currency'),
        );
    }
}
