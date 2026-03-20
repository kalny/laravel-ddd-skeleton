<?php

namespace Database\Seeders;

use App\Billing\Infrastructure\Persistence\Eloquent\Models\Account;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        Account::factory()->create([
            'user_id' => $user->id,
        ]);
    }
}
