<?php

namespace STS\LockingMigrations;

use Cache;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Migrations\Migrator;

/**
 *
 */
class LockingMigrateCommand extends MigrateCommand
{
    /**
     * @var Mutex
     */
    protected $mutex;

    /**
     * LockingMigrateCommand constructor.
     *
     * @param Migrator $migrator
     * @param Mutex    $mutex
     */
    public function __construct( Migrator $migrator, Mutex $mutex )
    {
        $this->mutex = $mutex;

        $this->signature .= "{--lock : Ensure only one server runs migrations at a time}";

        parent::__construct($migrator);
    }

    public function handle()
    {
        if ($this->option('lock') && !$this->mutex->acquire()) {
            $this->error("Unable to acquire lock to run migrations");
            exit(1);
        }

        parent::handle();

        $this->mutex->release();
    }
}