<?php

namespace Prajwal89\LaravelShareTo;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LaravelShareToServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Prajwal89\LaravelShareTo\ShareToController');
    }


    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            // 'prefix' => config('blogpackage.prefix'),
            // 'middleware' => config('blogpackage.middleware'),
        ];
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            // Export the migration
            $this->publishes([
                __DIR__ . '/../database/migrations/create_track_share.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_track_share.php'),
                // you can add any number of migrations here
            ], 'migrations');
        }

        $this->publishes([
            __DIR__ . '/../config/laravel-share-to.php' => config_path('laravel-share-to.php'),
        ], 'laravel-share-to-config');
    }
}
