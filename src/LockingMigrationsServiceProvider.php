<?php

namespace STS\LockingMigrations;

use Illuminate\Support\ServiceProvider;

class LockingMigrationsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        resolve('command.migrate');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->commands([
                'command.migrate.locking'
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/locking-migrations.php', 'locking-migrations');

        $this->app->singleton('command.migrate.locking', function ( $app ) {
            return new LockingMigrateCommand($app['migrator'], new Mutex($this->mutexCacheKey()));
        });
    }

    protected function mutexCacheKey()
    {
        return "migration_lock_" . md5(app_path());
    }

    public function provides()
    {
        return ['command.migrate.locking'];
    }
}
