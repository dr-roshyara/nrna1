<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Gate;

/**
 * The CLOSED set of expressible Committee positions: accept · object.
 * Adopted text defines no Committee abstention and this model does not invent one
 * (EM-ARCH-001 AG-2, G-1; D-9 — EM-OPEN-088 is the representative-side analogue,
 * unruled for the Committee).
 */
enum AcceptancePosition: string
{
    case Accept = 'accept';
    case Object = 'object';
}
