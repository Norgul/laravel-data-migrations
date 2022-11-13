<?php

namespace Norgul\DataMigrations\App\Console\Commands;

use Illuminate\Database\Console\Migrations\InstallCommand as LaravelInstallCommand;

class InstallCommand extends LaravelInstallCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'data-migrate:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the data migration repository';
}
