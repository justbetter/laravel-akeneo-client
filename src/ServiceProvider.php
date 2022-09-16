<?php

namespace JustBetter\AkeneoClient;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JustBetter\AkeneoClient\Actions\DispatchEvent;
use JustBetter\AkeneoClient\Actions\ResolveEvent;
use JustBetter\AkeneoClient\Client\Akeneo;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this
            ->registerConfig()
            ->registerClient()
            ->registerActions();
    }

    protected function registerConfig(): static
    {
        $this->mergeConfigFrom(__DIR__.'/../config/akeneo.php', 'akeneo');

        return $this;
    }

    protected function registerClient(): static
    {
        $this->app->singleton(Akeneo::class, fn () => new Akeneo(
            url: config('akeneo.url'),
            clientId: config('akeneo.client_id'),
            secret: config('akeneo.secret'),
            username: config('akeneo.username'),
            password: config('akeneo.password')
        ));

        return $this;
    }

    protected function registerActions(): static
    {
        DispatchEvent::bind();
        ResolveEvent::bind();

        return $this;
    }

    public function boot(): void
    {
        $this
            ->bootConfig()
            ->bootRoutes();
    }

    protected function bootConfig(): static
    {
        $this->publishes([
            __DIR__.'/../config/akeneo.php' => config_path('akeneo.php'),
        ], 'config');

        return $this;
    }

    protected function bootRoutes(): static
    {
        if (! $this->app->routesAreCached()) {
            Route::prefix(config('akeneo.prefix'))
                ->middleware(config('akeneo.middleware'))
                ->group(fn () => $this->loadRoutesFrom(__DIR__.'/../routes/api.php'));
        }

        return $this;
    }
}
