<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

/**
 * A version of this revision is published: the revision is closed to every act (published claims never change; a
 * correction is a new revision, forward-only, D-228). Nothing was written.
 */
final class RevisionAlreadyPublished extends \RuntimeException
{
    public function __construct(public readonly string $revisionId)
    {
        parent::__construct("Revision {$revisionId} is published: it is closed; a correction is a new revision.");
    }
}
