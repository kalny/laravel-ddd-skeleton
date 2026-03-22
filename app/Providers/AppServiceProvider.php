<?php

namespace App\Providers;

use App\Billing\Application\UseCases\Commands\OpenAccount\OpenAccount;
use App\Billing\Application\UseCases\Commands\OpenAccount\OpenAccountCommandHandler;
use App\Billing\Domain\Account\Repositories\AccountRepository;
use App\Billing\Domain\Policies\DefaultCurrencyPolicy;
use App\Billing\Infrastructure\Persistence\Eloquent\Repositories\EloquentAccountRepository;
use App\Billing\Infrastructure\Policies\DefaultCurrencyStaticPolicy;
use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Application\Services\TokenManager;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Infrastructure\Persistence\Eloquent\Repositories\EloquentUserRepository;
use App\Identity\Infrastructure\Services\LaravelPasswordHasher;
use App\Identity\Infrastructure\Services\LaravelTokenManager;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\EventBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Services\IdGenerator;
use App\Shared\Application\Services\TransactionManager;
use App\Shared\Infrastructure\Bus\LaravelCommandBus;
use App\Shared\Infrastructure\Bus\LaravelEventBus;
use App\Shared\Infrastructure\Bus\LaravelQueryBus;
use App\Shared\Infrastructure\Services\LaravelIdGenerator;
use App\Shared\Infrastructure\Services\LaravelTransactionManager;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        // Bus
        CommandBus::class => LaravelCommandBus::class,
        QueryBus::class => LaravelQueryBus::class,

        // Services
        TransactionManager::class => LaravelTransactionManager::class,
        IdGenerator::class => LaravelIdGenerator::class,
        PasswordHasher::class => LaravelPasswordHasher::class,
        EventBus::class => LaravelEventBus::class,
        TokenManager::class => LaravelTokenManager::class,

        // Repositories
        UserRepository::class => EloquentUserRepository::class,
        AccountRepository::class => EloquentAccountRepository::class,

        // Handlers
        OpenAccount::class => OpenAccountCommandHandler::class,

        // Policies
        DefaultCurrencyPolicy::class => DefaultCurrencyStaticPolicy::class,
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
