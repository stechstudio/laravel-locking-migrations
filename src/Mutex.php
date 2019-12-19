<?php

namespace STS\LockingMigrations;

use Cache;

class Mutex
{
    /**
     * @var string
     */
    protected $cacheKey;

    /**
     * @var int
     */
    protected $lockLifetime;

    public function __construct( $cacheKey, $lockLifetime = 600 )
    {
        $this->cacheKey = $cacheKey;
        $this->lockLifetime = $lockLifetime;
    }

    /**
     * @param int $maxWait
     *
     * @return bool
     */
    public function acquire( $maxWait = 120 ): bool
    {
        for ($waited = 0; Cache::has($this->cacheKey) && $waited < $maxWait; $waited++) {
            sleep(1);
        }

        return Cache::add($this->cacheKey, microtime(), $this->lockLifetime);
    }

    public function release(): void
    {
        Cache::forget($this->cacheKey);
    }
}