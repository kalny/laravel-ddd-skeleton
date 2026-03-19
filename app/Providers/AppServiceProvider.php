<?php

namespace App\Providers;

use App\Application\Services\TransactionManager;
use App\Application\Services\EventDispatcher;
use App\Application\Services\IdGenerator;
use App\Application\Services\PasswordHasher;
use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentUserRepository;
use App\Infrastructure\Services\LaravelEventDispatcher;
use App\Infrastructure\Services\LaravelIdGenerator;
use App\Infrastructure\Services\LaravelPasswordHasher;
use App\Infrastructure\Services\LaravelTransactionManager;
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
