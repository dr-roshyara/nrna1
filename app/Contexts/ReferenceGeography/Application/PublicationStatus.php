<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

enum PublicationStatus: string
{
    case Published = 'published';
    /** This content version is already published: nothing written (idempotent). */
    case AlreadyPublished = 'already-published';
    /** Governance refused it under the lock: nothing written. */
    case Refused = 'refused';
    /** A publication after p₀ matched a recorded predicate: marked Stale, nothing published (v3.11 §5.3). */
    case Stale = 'stale';
}
