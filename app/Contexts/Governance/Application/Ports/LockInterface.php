<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Ports;

interface LockInterface
{
    public function acquire(string $key, int $ttlSeconds): bool;

    public function release(string $key): void;
}
