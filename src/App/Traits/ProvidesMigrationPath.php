<?php

namespace Norgul\DataMigrations\App\Traits;

use Illuminate\Support\Facades\File;

trait ProvidesMigrationPath
{
    protected function getMigrationPath()
    {
        $path = $this->laravel->databasePath() . DIRECTORY_SEPARATOR . 'data-migrations';

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }

        return $path;
    }
}