<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

/** Identity cannot establish an answer now (e.g. its store is unreachable). Callers refuse; they never guess. */
final class IdentityUnavailable extends \RuntimeException
{
}
