<?php

namespace Gwd\LaravelNovaConfigs;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Gwd\LaravelNovaConfigs\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/database/migrations/2018_11_21_000000_create_laravel_nova_configs_table.php'
            => base_path('database/migrations/2018_11_21_000000_create_laravel_nova_configs_table.php'),

            __DIR__ . '/config/laravel-nova-config.php'
            => base_path('config/laravel-nova-config.php'),
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-nova-configs');

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            //
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/laravel-nova-configs')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
