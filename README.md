# Laravel Data Migrations

This package is used to deliver seamless data migrations to Laravel. Data migrations are sort of a VCS
for your mandatory DB data without which the app wouldn't function.

One alternative is to burn these within your plain migrations, other is just to use seeders at which point
you need to devise a mechanism to split production grade seeders from the development ones.

These are all good solutions as long as you follow some methodology and are consistent. The goal of this 
package was to deliver a real separation for this use case.

# Installation

Require the package with ``composer require norgul/laravel-data-migrations``.
Service provider will be registered automatically.

# Usage

You can start using the package by running these commands which are following Laravel ``migration`` command
variants with a mostly same API they provide. Remember you can add ``-h`` flag to show all available options
for a particular command.

## Make Command

``php artisan make:data-migration {name}`` 

Create your first data migration. This will create `data-migrations` directory in your ``database`` 
directory if it doesn't already exist, which is the place where you'll find already familiar naming structure.

Package is using Laravel migrator in the background, so if you run something like:
```
php artisan make:data-migration add_user_types_to_users_table
```

The table guesser will automatically add a data migration with ``users`` table stubbed in. 

Even though you can reference Eloquent classes and even your seeders from within data migrations, it is 
**highly recommended** to use ``DB`` facade to execute queries. Reason behind it is that your classes and seeders may
change over time, thus leaving your data migrations execute differently in the future. Always using ``DB``
facade will ensure that end results are always the same.

## Data Migrate Command

``php artisan data-migrate``

Execute your migrations with ``data-migrate`` command. These will be executed once, in the same way as standard
migrations are ran. Command will automatically create ``data_migrations`` table in the DB if it doesn't already 
exist. It is using ``php artisan data-migrate:install`` command in the background, but you don't need to explicitly
run it. 

