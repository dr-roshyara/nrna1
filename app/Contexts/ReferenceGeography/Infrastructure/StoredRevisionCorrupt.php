<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

/**
 * A stored revision cannot be rebuilt as a state the domain could have reached (tampered content, a broken digest,
 * an invalid or incomplete record): a data-integrity incident. Never repaired silently (G-4 interpretation §3).
 */
final class StoredRevisionCorrupt extends \RuntimeException
{
    public function __construct(public readonly string $revisionId, \Throwable $cause)
    {
        parent::__construct("Stored revision {$revisionId} is corrupt: {$cause->getMessage()}", 0, $cause);
    }
}
