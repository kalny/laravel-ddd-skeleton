<?php

namespace App\Billing\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $user_id
 * @property int $balance
 * @property string $currency
 */
class Account extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'balance',
        'currency',
    ];
}
