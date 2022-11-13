<?php

declare(strict_types=1);

namespace Norgul\DataMigrations;

use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\ServiceProvider;
use Norgul\DataMigrations\App\Console\Commands\InstallCommand;
use Norgul\DataMigrations\App\Console\Commands\MigrateCommand;
use Norgul\DataMigrations\App\Console\Commands\MigrateMakeCommand;

class DataMigrationsServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'DataMigrate'        => 'command.data-migrate',
        //        'DataMigrateFresh'    => 'command.data-migrate.fresh',
        'DataMigrateInstall' => 'command.data-migrate.install',
        //        'DataMigrateRefresh'  => 'command.data-migrate.refresh',
        //        'DataMigrateReset'    => 'command.data-migrate.reset',
        //        'DataMigrateRollback' => 'command.data-migrate.rollback',
        //        'DataMigrateStatus'   => 'command.data-migrate.status',
        'DataMigrateMake'    => 'command.data-migrate.make',
    ];

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
            __DIR__ . '/../config/data-migrations.php' => config_path('data-migrations.php'),
        ], 'data-migrations');

        $this->app->when(MigrationCreator::class)
            ->needs('$customStubPath')
            ->give(function ($app) {
                return $app->basePath('data-migrations');
            });

        $this->registerRepository();
        $this->registerMigrator();
        $this->registerCreator();
        $this->registerCommands($this->commands);
    }

    /**
     * Register the migration repository service.
     *
     * @return void
     */
    protected function registerRepository()
    {
        $this->app->singleton('data-migration.repository', function ($app) {
            return new DatabaseMigrationRepository($app['db'], config('data-migrations.table'));
        });
    }

    /**
     * Register the migrator service.
     *
     * @return void
     */
    protected function registerMigrator()
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.
        $this->app->singleton('data-migrator', function ($app) {
            $repository = $app['data-migration.repository'];

            return new Migrator($repository, $app['db'], $app['files'], $app['events']);
        });
    }

    /**
     * Register the migration creator.
     *
     * @return void
     */
    protected function registerCreator()
    {
        $this->app->singleton('data-migration.creator', function ($app) {
            return new MigrationCreator($app['files'], $app->basePath('stubs'));
        });
    }

    /**
     * Register the given commands.
     *
     * @param array $commands
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            $this->{"register{$command}Command"}();
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerDataMigrateCommand()
    {
        $this->app->singleton('command.data-migrate', function ($app) {
            return new MigrateCommand($app['data-migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerDataMigrateFreshCommand()
    {
        $this->app->singleton('command.data-migrate.fresh', function () {
            return new \Illuminate\Database\Console\Migrations\FreshCommand;
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerDataMigrateInstallCommand()
    {
        $this->app->singleton('command.data-migrate.install', function ($app) {
            return new InstallCommand($app['data-migration.repository']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerDataMigrateMakeCommand()
    {
        $this->app->singleton('command.data-migrate.make', function ($app) {
            // Once we have the migration creator registered, we will create the command
            // and inject the creator. The creator is responsible for the actual file
            // creation of the migrations, and may be extended by these developers.
            $creator = $app['data-migration.creator'];

            $composer = $app['composer'];

            return new MigrateMakeCommand($creator, $composer);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerDataMigrateRefreshCommand()
    {
        $this->app->singleton('command.data-migrate.refresh', function () {
            return new \Illuminate\Database\Console\Migrations\RefreshCommand;
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerDataMigrateResetCommand()
    {
        $this->app->singleton('command.data-migrate.reset', function ($app) {
            return new ResetCommand($app['migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerDataMigrateRollbackCommand()
    {
        $this->app->singleton('command.data-migrate.rollback', function ($app) {
            return new RollbackCommand($app['migrator']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerDataMigrateStatusCommand()
    {
        $this->app->singleton('command.data-migrate.status', function ($app) {
            return new StatusCommand($app['migrator']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_merge([
            'data-migrator', 'data-migration.repository', 'data-migration.creator',
        ], array_values($this->commands));
    }
}
