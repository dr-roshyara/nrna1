<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

/** v3.11 §6: a close through τ was refused (τ is in the future, or the lock is busy): nothing confirmed; retry later. */
final class BoundaryNotClosed extends \RuntimeException
{
    public function __construct(string $why)
    {
        parent::__construct("BOUNDARY_NOT_CLOSED: {$why}");
    }
}
