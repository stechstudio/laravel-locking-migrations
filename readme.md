# Locking migrations for Laravel

If your app deployment process deploys to multiple instances at the same time, and each of those attempts to run migrations, you can run into problems.

This package adds a `--lock` option to the `php artisan migrate` command, which will ensure only one server runs migrations at a time.

## Installation

Via Composer

``` bash
$ composer require stechstudio/laravel-locking-migrations
```

## Usage

In your composer.json `post-install-cmd` hook (or wherever you are running migrations during deploy) simply add the `--lock` option.

``` php
php artisan migrate --lock
```