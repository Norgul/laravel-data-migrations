<?php

namespace Norgul\DataMigrations\App\Console\Commands;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as LaravelMigrateMakeCommand;
use Norgul\DataMigrations\App\Traits\ProvidesMigrationPath;

class MigrateMakeCommand extends LaravelMigrateMakeCommand
{
    use ProvidesMigrationPath;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:data-migration {name : The name of the migration}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--fullpath : Output the full path of the migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new data migration file';
}
