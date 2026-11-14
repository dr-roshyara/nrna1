<?php

declare(strict_types=1);

namespace Tests\Support\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\Ports\LockedLedger;
use App\Contexts\ReferenceGeography\Application\Ports\PublicationLedger;
use App\Contexts\ReferenceGeography\Application\StoredRevision;
use App\Contexts\ReferenceGeography\Domain\GovernanceDecision;
use App\Contexts\ReferenceGeography\Domain\GovernanceNotice;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;

/**
 * TEST ONLY (G-6c-3): the ledger port in memory, with no publications: the roster and the notices it receives. Work
 * that throws leaves both unchanged, as the PostgreSQL rollback does. Publication methods are not supported.
 */
final class InMemoryPublicationLedger implements PublicationLedger
{
    /** @var list<GovernanceNotice> */
    public array $notices = [];

    public function __construct(public readonly InMemoryStewardRosterStore $rosters = new InMemoryStewardRosterStore())
    {
    }

    public function withLock(\Closure $work): mixed
    {
        $locked = new class ($this->rosters->current()) implements LockedLedger {
            /** @var list<GovernanceNotice> */
            public array $notices = [];
            public ?StewardRoster $recorded = null;

            public function __construct(private readonly StewardRoster $committed)
            {
            }

            public function roster(): StewardRoster
            {
                return StewardRoster::fromEntries($this->committed->entries());
            }

            public function recordRoster(StewardRoster $roster): void
            {
                $this->recorded = $roster;
            }

            public function publicationsInvolving(array $persons): array
            {
                return [];
            }

            public function lifecycleFor(array $positions): array
            {
                return [];
            }

            public function appendNotice(GovernanceNotice $notice): int
            {
                $this->notices[] = $notice;

                return count($this->notices);
            }

            public function revision(RevisionId $id): StoredRevision
            {
                throw new \LogicException('no publications in memory');
            }

            public function isPublished(RevisionId $id, int $contentVersion): bool
            {
                return false;
            }

            public function publishedAfter(int $p0): array
            {
                return [];
            }

            public function saveRevision(Revision $revision, int $expectedRowVersion): void
            {
                throw new \LogicException('no publications in memory');
            }

            public function append(Revision $revision, GovernanceDecision $decision): int
            {
                throw new \LogicException('no publications in memory');
            }
        };
        $result = $work($locked);
        if ($locked->recorded !== null) {
            $this->rosters->commit($locked->recorded);
        }
        $this->notices = [...$this->notices, ...$locked->notices];

        return $result;
    }

    public function closeThrough(?string $tau): string
    {
        throw new \LogicException('no clock in memory');
    }
}
