<?php

namespace Norgul\DataMigrations\App\Console\Commands;

use Illuminate\Database\Console\Migrations\RollbackCommand as LaravelRollbackCommand;
use Norgul\DataMigrations\App\Traits\ProvidesMigrationPath;

class RollbackCommand extends LaravelRollbackCommand
{
    use ProvidesMigrationPath;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'data-migrate:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback the last data migration';
}
