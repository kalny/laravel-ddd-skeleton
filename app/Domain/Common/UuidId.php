<?php

namespace App\Domain\Common;

use App\Domain\Common\Exceptions\InvalidUuidException;

final readonly class UuidId
{
    public function __construct(private string $value)
    {
        if (!$this->isCorrectUuid($value)) {
            throw InvalidUuidException::withUuid($value);
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function isCorrectUuid(string $uuid): bool
    {
        return filter_var($uuid, FILTER_VALIDATE_REGEXP, [
            'options' => [
                'regexp' => "/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i"
            ]
        ]);
    }
}
