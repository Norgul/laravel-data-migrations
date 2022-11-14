# Laravel Data Migrations

This package is used to deliver seamless data migrations to Laravel. Data migrations are version control system
for your mandatory DB data (like statuses, types, etc.) without which the app wouldn't function.

One alternative is to burn these within your plain migrations, other is just to use seeders at which point
you need to devise a mechanism to split production grade seeders from the development ones.

These are all good solutions as long as you follow some methodology and are consistent. The goal of this 
package was to deliver a real separation for this use case.

# Installation

Require the package with ``composer require norgul/laravel-data-migrations``.
Service provider will be registered automatically.

# Usage

You can start using the package by running commands below which are following Laravel ``migration`` command
structure with, for the most part, the same API they provide. 

Remember you can add ``-h`` flag to show all available options for a particular command.

# Commands
## Make Data Migration

``php artisan make:data-migration {name}`` 

Create your first data migration. This will create `data-migrations` directory in your ``database`` 
directory if it doesn't already exist, which is the place where you'll find already familiar naming structure.

Package is using Laravel migrator in the background, so if you run:
```
php artisan make:data-migration add_user_types_to_users_table
```

The table guesser will automatically add a data migration with ``users`` table stubbed in. 

Even though you can reference Eloquent classes and even your seeders from within data migrations, it is 
**highly recommended** to use ``DB`` facade to execute queries. Reason behind it is that your classes and seeders may
change over time, thus leaving your data migrations execute differently in the future. Always using ``DB``
facade will ensure that end results are always the same.

## Data Migrate

``php artisan data-migrate``

Execute your migrations with ``data-migrate`` command. These will be executed once, in the same way as standard
migrations are ran (by executing `up()` function).

Command will automatically create ``data_migrations`` table in the DB if it doesn't already exist.

## Data Migrate Rollback

``php artisan data-migrate:rollback``

You can revert your changes by executing this command which will trigger ``down()`` function in the data migration.

## Data Migrate Install

``php artisan data-migrate:install``

You never need to run this command explicitly. It is here for documentation purposes.

This command will create ``data_migrations`` table in your DB.
