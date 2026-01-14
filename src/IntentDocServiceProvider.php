<?php

namespace IntentDoc\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Route;

class IntentDocServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(IntentRegistry::class);
    }

    public function boot(): void
    {
        Route::macro('intent', function (string $name) {
            return new RouteIntentBuilder($this, $name);
        });

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'intent-doc');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\GenerateIntentDocCommand::class,
            ]);
        }
    }
}
