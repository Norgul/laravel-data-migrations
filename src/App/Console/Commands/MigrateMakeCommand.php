<?php

namespace Norgul\DataMigrations\App\Console\Commands;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as LaravelMigrateMakeCommand;
use Illuminate\Support\Facades\File;

class MigrateMakeCommand extends LaravelMigrateMakeCommand
{
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

    protected function getMigrationPath()
    {
        $path = $this->laravel->databasePath() . DIRECTORY_SEPARATOR . 'data-migrations';

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }

        return $path;
    }
}
