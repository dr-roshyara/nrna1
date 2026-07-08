<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Exception;

use RuntimeException;

/**
 * Application business condition: this determination has ALREADY been applied to its
 * Challenge (the same constitutional fact, re-delivered). A semantic replay — not an
 * error, not a messaging concept. The inbox translator maps it to an idempotent replay.
 */
final class DeterminationAlreadyApplied extends RuntimeException
{
}
