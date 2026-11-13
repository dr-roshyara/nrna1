<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

/** The stored revision changed since it was loaded (or already exists): nothing was written; reload and retry. */
final class RevisionChangedConcurrently extends \RuntimeException
{
    public function __construct(public readonly string $revisionId)
    {
        parent::__construct("Revision {$revisionId} changed concurrently; nothing was written.");
    }
}
