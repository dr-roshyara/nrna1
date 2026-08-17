<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Port;

use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * Driven port: supplies RECORDING INSTANTS only — no schedule semantics, no
 * civil-time meaning (D-8; EM-OPEN-024 open: no implementation may fix the meaning
 * of an entered time; EM-ARCH-001 §5c). The domain core never reads a clock
 * itself; instants arrive as recorded facts. Nothing about this port may ever be
 * attached to an OPEN gate or to inaction (G-3; D-4).
 */
interface InstantSource
{
    public function now(): RecordedInstant;
}
