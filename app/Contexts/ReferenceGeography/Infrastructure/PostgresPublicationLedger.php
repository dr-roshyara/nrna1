<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Application\BoundaryNotClosed;
use App\Contexts\ReferenceGeography\Application\ClockNotPastBoundary;
use App\Contexts\ReferenceGeography\Application\Ports\LockedLedger;
use App\Contexts\ReferenceGeography\Application\Ports\PublicationLedger;
use App\Contexts\ReferenceGeography\Application\StoredRevision;
use App\Contexts\ReferenceGeography\Domain\GovernanceDecision;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;

/**
 * The Geography knowledge stream in PostgreSQL (v3.11 §6; completion §4; G-4 interpretation §2). The only writer of
 * refgeo_publications and of closed_through_time.
 *
 * Lock order (the same for every writer, so no deadlock): one transaction, `SET TRANSACTION ISOLATION LEVEL READ
 * COMMITTED` first, then `refgeo_sequence … FOR UPDATE`. The roster store (G-4c) takes the very same row first, so
 * publication and roster writes are serialised; a waiter re-reads the committed row (READ COMMITTED).
 *
 * This class decides nothing about authority: PublishRevision (Application) calls the domain under the lock; this
 * adapter supplies the locked reads, the clock rule (refuse, never adjust) and the append. The G-4a triggers re-check
 * the position and the clock as a backstop.
 *
 * Test seams: $beforeCommit runs after the work, before commit (failure injection, visibility); $clockSql replaces
 * `clock_timestamp()` (clock regression).
 */
final class PostgresPublicationLedger implements PublicationLedger
{
    public function __construct(
        private readonly ConnectionInterface $db,
        private readonly ?\Closure $beforeCommit = null,
        private readonly string $clockSql = 'clock_timestamp()',
    ) {
    }

    public function withLock(\Closure $work): mixed
    {
        if ($this->db->transactionLevel() > 0) {
            throw new \LogicException('Publication starts its own transaction: the isolation level and the lock must be its first statements.');
        }

        return $this->db->transaction(function () use ($work): mixed {
            $this->db->statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
            $this->db->selectOne('SELECT last_position FROM refgeo_sequence WHERE id = 1 FOR UPDATE');
            $result = $work($this->locked());
            if ($this->beforeCommit !== null) {
                ($this->beforeCommit)();
            }

            return $result;
        });
    }

    public function closeThrough(?string $tau): string
    {
        if ($tau !== null) {
            $known = $this->db->selectOne('SELECT closed_through_time IS NOT NULL AND ?::timestamptz <= closed_through_time AS confirmed, closed_through_time::text AS w FROM refgeo_sequence WHERE id = 1', [$tau]);
            if ($known->confirmed) {
                return $known->w;   // W only grows and is committed: confirmed without the lock (v3.11 §6)
            }
        }
        if ($this->db->transactionLevel() > 0) {
            throw new \LogicException('A close starts its own transaction.');
        }
        try {
            return $this->db->transaction(function () use ($tau): string {
                $this->db->statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
                $row = $this->db->selectOne("SELECT {$this->clockSql} AS c, closed_through_time::text AS w FROM refgeo_sequence WHERE id = 1 FOR UPDATE NOWAIT");
                $target = $tau ?? $row->c;
                $check = $this->db->selectOne('SELECT ?::timestamptz <= ?::timestamptz AS reached, (?::timestamptz IS NOT NULL AND ?::timestamptz <= ?::timestamptz) AS covered, ?::timestamptz::text AS target',
                    [$target, $row->c, $row->w, $target, $row->w, $target]);
                if (!$check->reached) {
                    throw new BoundaryNotClosed('τ is in the future');
                }
                if ($check->covered) {
                    return $row->w;
                }
                $this->db->update('UPDATE refgeo_sequence SET closed_through_time = ?::timestamptz WHERE id = 1', [$check->target]);

                return $check->target;
            });
        } catch (QueryException $e) {
            if (($e->errorInfo[0] ?? '') === '55P03') {
                throw new BoundaryNotClosed('the publication lock is busy; nothing was closed');
            }
            throw $e;
        }
    }

    private function locked(): LockedLedger
    {
        return new class ($this->db, $this->clockSql) implements LockedLedger {
            private readonly PostgresRevisionRepository $revisions;

            public function __construct(private readonly ConnectionInterface $db, private readonly string $clockSql)
            {
                $this->revisions = new PostgresRevisionRepository($db);
            }

            public function revision(RevisionId $id): StoredRevision
            {
                return $this->revisions->get($id);
            }

            public function roster(): StewardRoster
            {
                return (new PostgresStewardRosterStore($this->db))->current();
            }

            public function isPublished(RevisionId $id, int $contentVersion): bool
            {
                return $this->db->table('refgeo_publications')->where('revision_id', $id->value())->where('content_version', $contentVersion)->exists();
            }

            public function publishedAfter(int $p0): array
            {
                return array_map(static fn (object $row): PublishedClaim => new PublishedClaim(
                    PostgresRevisionRepository::claimOf($row),
                    PostgresRevisionRepository::beforeImageFromJson($row->before_image),
                    (int) $row->position,
                ), $this->db->select('SELECT c.claim_id, c.slot_key, c.value, c.valid_from, c.valid_to, c.basis_kind, c.basis_reference, c.before_image::text AS before_image, p.position
                    FROM refgeo_claims c JOIN refgeo_publications p ON p.revision_id = c.revision_id AND p.content_version = c.content_version
                    WHERE p.position > ? ORDER BY p.position, c.claim_id', [$p0]));
            }

            public function saveRevision(Revision $revision, int $expectedRowVersion): void
            {
                $this->revisions->save($revision, $expectedRowVersion);
            }

            public function append(Revision $revision, GovernanceDecision $decision): int
            {
                $id = $revision->id()->value();
                $version = $revision->currentVersion()?->number ?? throw new \LogicException('Only a submitted content version is published.');
                if (!$this->db->table('refgeo_claims')->where('revision_id', $id)->where('content_version', $version)->exists()) {
                    throw new \RuntimeException("Revision {$id} version {$version} has no frozen claims; it cannot be published.");
                }
                $clock = $this->db->selectOne("SELECT x.c::text AS c, (x.c > coalesce(s.closed_through_time, '-infinity') AND x.c >= coalesce(s.last_recorded_at, '-infinity')) AS past, s.last_position
                    FROM (SELECT {$this->clockSql} AS c) x, refgeo_sequence s WHERE s.id = 1");
                if (!$clock->past) {
                    throw new ClockNotPastBoundary();
                }
                $position = (int) $clock->last_position + 1;
                $this->db->insert("INSERT INTO refgeo_publications (position, kind, revision_id, content_version, roster_version, governance_rule, independence, recorded_at)
                    VALUES (?, 'revision', ?, ?, ?, ?, ?, ?::timestamptz)",
                    [$position, $id, $version, $decision->rosterVersion, $decision->rule->value, $decision->independence, $clock->c]);

                return $position;
            }
        };
    }
}
