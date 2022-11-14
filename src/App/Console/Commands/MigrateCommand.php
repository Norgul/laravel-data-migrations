<?php

namespace Norgul\DataMigrations\App\Console\Commands;

use Illuminate\Database\Console\Migrations\MigrateCommand as LaravelMigrateCommand;
use Norgul\DataMigrations\App\Traits\ProvidesMigrationPath;

class MigrateCommand extends LaravelMigrateCommand
{
    use ProvidesMigrationPath;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data-migrate {--database= : The database connection to use}
                {--force : Force the operation to run when in production}
                {--path=* : The path(s) to the migrations files to be executed}
                {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
                {--pretend : Dump the SQL queries that would be run}
                {--step : Force the migrations to be run so they can be rolled back individually}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the data migrations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return 1;
        }

        $this->migrator->usingConnection($this->option('database'), function () {
            $this->prepareDatabase();

            // Next, we will check to see if a path option has been defined. If it has
            // we will use the path relative to the root of this installation folder
            // so that migrations may be run for any path within the applications.
            $this->migrator->setOutput($this->output)
                ->run($this->getMigrationPaths(), [
                    'pretend' => $this->option('pretend'),
                    'step'    => $this->option('step'),
                ]);
        });

        return 0;
    }

    /**
     * Prepare the migration database for running.
     *
     * @return void
     */
    protected function prepareDatabase()
    {
        if (!$this->migrator->repositoryExists()) {
            $this->call('data-migrate:install', array_filter([
                '--database' => $this->option('database'),
            ]));
        }
    }
}
