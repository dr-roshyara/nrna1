<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Application\ClockNotPastBoundary;
use App\Contexts\ReferenceGeography\Application\StoredRevision;
use App\Contexts\ReferenceGeography\Domain\GovernanceDecision;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;

/** What publication may read and write while it holds the lock (all as last committed; nothing else can change it). */
interface LockedLedger
{
    public function revision(RevisionId $id): StoredRevision;

    public function roster(): StewardRoster;

    public function isPublished(RevisionId $id, int $contentVersion): bool;

    /** @return list<PublishedClaim> the claims published at positions in (p₀, the committed position], with their before-images */
    public function publishedAfter(int $p0): array;

    public function saveRevision(Revision $revision, int $expectedRowVersion): void;

    /**
     * Appends the publication of the revision's current content version at the next position, with the decision's
     * roster version, rule and independence statement, and recorded_at = the database clock read now.
     *
     * @throws ClockNotPastBoundary
     */
    public function append(Revision $revision, GovernanceDecision $decision): int;
}
