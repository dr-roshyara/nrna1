<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Application\ClockNotPastBoundary;
use App\Contexts\ReferenceGeography\Application\PublicationOfRevision;
use App\Contexts\ReferenceGeography\Application\StoredRevision;
use App\Contexts\ReferenceGeography\Domain\FindingLifecycle;
use App\Contexts\ReferenceGeography\Domain\GovernanceDecision;
use App\Contexts\ReferenceGeography\Domain\GovernanceNotice;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
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
     * roster version, rule and independence statement, and recorded_at = the database clock read now. A second-person
     * publication also indexes, in the same transaction, every approver of that version and every excluded person
     * (G-6c, the publication-person index).
     *
     * @throws ClockNotPastBoundary
     */
    public function append(Revision $revision, GovernanceDecision $decision): int;

    /**
     * G-6c-3: stores the roster entries made on $roster since it was read with roster() (an Identity merge or reversal
     * entry is the consumer checkpoint: unique per merge id and kind).
     */
    public function recordRoster(StewardRoster $roster): void;

    /**
     * G-6c-3: the second-person publications whose indexed persons (approvers, excluded, confirmers) include any of
     * $persons, in position order. A superset of the affected ones: the domain decides each.
     *
     * @param list<PersonReference> $persons
     * @return list<PublicationOfRevision>
     */
    public function publicationsInvolving(array $persons): array;

    /**
     * G-6c-3: the D-90 lifecycle of each publication (its findings, their confirmations and closures, its published
     * corrections), as committed.
     *
     * @param list<int> $positions
     * @return array<int, FindingLifecycle>
     */
    public function lifecycleFor(array $positions): array;

    /**
     * G-6c-4: the second-person publication that the FINDING at $finding concerns; null if no finding is stored there.
     */
    public function findingConcerning(int $finding): ?PublicationOfRevision;

    /**
     * G-6c-3: appends a governance notice at the next position, at the notice's roster version, with the G-4 clock rule.
     * G-6c-4: a confirmation also indexes the confirmer with the publication its finding concerns (role confirmer).
     *
     * @throws ClockNotPastBoundary
     */
    public function appendNotice(GovernanceNotice $notice): int;
}
