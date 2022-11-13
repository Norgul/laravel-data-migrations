<?php

declare(strict_types=1);

namespace Norgul\DataMigrations;

use Illuminate\Support\ServiceProvider;

class DataMigrationsServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/data-migrations.php', 'data-migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../migrations' => database_path('migrations'),
        ], 'data-migrations');

        $this->publishes([
            __DIR__ . '/../config/data-migrations.php' => config_path('data-migrations.php'),
        ], 'data-migrations');
    }
}
