<?php

namespace App\Providers;

use App\Billing\Application\UseCases\OpenAccount\OpenAccount;
use App\Billing\Application\UseCases\OpenAccount\OpenAccountHandler;
use App\Billing\Domain\Account\Repositories\AccountRepository;
use App\Billing\Infrastructure\Persistence\Eloquent\Repositories\EloquentAccountRepository;
use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Infrastructure\Persistence\Eloquent\Repositories\EloquentUserRepository;
use App\Identity\Infrastructure\Services\LaravelPasswordHasher;
use App\Shared\Application\Services\EventDispatcher;
use App\Shared\Application\Services\IdGenerator;
use App\Shared\Application\Services\TransactionManager;
use App\Shared\Infrastructure\Services\LaravelEventDispatcher;
use App\Shared\Infrastructure\Services\LaravelIdGenerator;
use App\Shared\Infrastructure\Services\LaravelTransactionManager;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        TransactionManager::class => LaravelTransactionManager::class,
        IdGenerator::class => LaravelIdGenerator::class,
        PasswordHasher::class => LaravelPasswordHasher::class,
        EventDispatcher::class => LaravelEventDispatcher::class,
        UserRepository::class => EloquentUserRepository::class,
        AccountRepository::class => EloquentAccountRepository::class,
        OpenAccount::class => OpenAccountHandler::class
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            $factoryNamespace = 'Database\\Factories\\';
            $modelBaseName = class_basename($modelName);
            return $factoryNamespace . $modelBaseName . 'Factory';
        });
    }
}
