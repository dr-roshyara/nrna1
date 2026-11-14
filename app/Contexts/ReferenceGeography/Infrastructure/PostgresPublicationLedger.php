<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Application\BoundaryNotClosed;
use App\Contexts\ReferenceGeography\Application\ClockNotPastBoundary;
use App\Contexts\ReferenceGeography\Application\Ports\LockedLedger;
use App\Contexts\ReferenceGeography\Application\Ports\PublicationLedger;
use App\Contexts\ReferenceGeography\Application\PublicationOfRevision;
use App\Contexts\ReferenceGeography\Application\StoredRevision;
use App\Contexts\ReferenceGeography\Domain\FindingLifecycle;
use App\Contexts\ReferenceGeography\Domain\GovernanceDecision;
use App\Contexts\ReferenceGeography\Domain\GovernanceNotice;
use App\Contexts\ReferenceGeography\Domain\GovernanceRule;
use App\Contexts\ReferenceGeography\Domain\NoticeKind;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
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
            /** governance_rule of a D-90 notice row (the column is NOT NULL; a notice is no governance decision) */
            private const NOTICE_RULE = 'd-90-notice';

            private readonly PostgresRevisionRepository $revisions;

            public function __construct(private readonly ConnectionInterface $db, private readonly string $clockSql)
            {
                $this->revisions = new PostgresRevisionRepository($db);
            }

            public function revision(RevisionId $id): StoredRevision
            {
                // lock order: refgeo_sequence (taken in withLock), then this revision's row; a save takes only the
                // revision row, so a save racing this publication waits for it and is then refused (G-4f)
                $this->db->selectOne('SELECT id FROM refgeo_revisions WHERE id = ? FOR UPDATE', [$id->value()]);

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
                [$position, $recordedAt] = $this->next();
                $this->db->insert("INSERT INTO refgeo_publications (position, kind, revision_id, content_version, roster_version, governance_rule, independence, recorded_at)
                    VALUES (?, 'revision', ?, ?, ?, ?, ?, ?::timestamptz)",
                    [$position, $id, $version, $decision->rosterVersion, $decision->rule->value, $decision->independence, $recordedAt]);
                if ($decision->rule === GovernanceRule::SecondPersonApproved) {
                    // G-6c: who took part, for the D-90 impact of later merges (a superset of the counted approver, §17)
                    $rows = [];
                    foreach ($revision->countingApprovals() as $approval) {
                        $rows[$approval->by->value() . '/approver'] = ['position' => $position, 'person' => $approval->by->value(), 'role' => 'approver'];
                    }
                    foreach ($revision->excludedPersons() as $person) {
                        $rows[$person->value() . '/excluded'] = ['position' => $position, 'person' => $person->value(), 'role' => 'excluded'];
                    }
                    $this->db->table('refgeo_publication_persons')->insert(array_values($rows));
                }

                return $position;
            }

            public function recordRoster(StewardRoster $roster): void
            {
                $stored = (int) $this->db->selectOne('SELECT last_roster_version AS v FROM refgeo_sequence WHERE id = 1')->v;
                (new PostgresStewardRosterStore($this->db))->storeEntriesAfter($roster, $stored);
            }

            public function publicationsInvolving(array $persons): array
            {
                if ($persons === []) {
                    return [];
                }
                $rows = $this->db->table('refgeo_publication_persons as pp')->join('refgeo_publications as p', 'p.position', '=', 'pp.position')
                    ->where('p.kind', 'revision')->whereIn('pp.person', array_map(static fn (PersonReference $p): string => $p->value(), $persons))
                    ->distinct()->orderBy('p.position')->get(['p.position', 'p.revision_id', 'p.roster_version']);

                return array_values(array_map(fn (object $row): PublicationOfRevision => new PublicationOfRevision(
                    (int) $row->position,
                    $this->revisions->get(RevisionId::fromString($row->revision_id))->revision,
                    (int) $row->roster_version,
                ), $rows->all()));
            }

            public function lifecycleFor(array $positions): array
            {
                if ($positions === []) {
                    return [];
                }
                $notices = $corrections = [];
                $findings = $this->db->table('refgeo_publications')->where('notice_kind', 'finding')->whereIn('concerns_position', $positions)
                    ->orderBy('position')->get(['position', 'concerns_position', 'roster_version', $this->db->raw('merge_basis::text AS merge_basis')]);
                $publicationOf = [];
                foreach ($findings as $f) {
                    $publicationOf[(int) $f->position] = (int) $f->concerns_position;
                    $notices[(int) $f->concerns_position][] = [(int) $f->position, GovernanceNotice::finding((int) $f->concerns_position, json_decode($f->merge_basis, true, 4, JSON_THROW_ON_ERROR), (int) $f->roster_version)];
                }
                if ($publicationOf !== []) {
                    foreach ($this->db->table('refgeo_publications')->whereIn('notice_kind', ['confirmation', 'closure'])->whereIn('concerns_position', array_keys($publicationOf))
                        ->orderBy('position')->get(['position', 'notice_kind', 'concerns_position', 'roster_version', 'ended_by', 'confirmed_by', 'confirmed_digest']) as $r) {
                        $finding = (int) $r->concerns_position;
                        $notices[$publicationOf[$finding]][] = [(int) $r->position, $r->notice_kind === 'closure'
                            ? GovernanceNotice::closure($finding, $r->ended_by, (int) $r->roster_version)
                            : GovernanceNotice::confirmation($finding, PersonReference::fromString(trim($r->confirmed_by)), $r->confirmed_digest, (int) $r->roster_version)];
                    }
                }
                foreach ($this->db->table('refgeo_publications')->where('kind', 'revision')->whereIn('corrects_position', $positions)->get(['position', 'corrects_position']) as $c) {
                    $corrections[(int) $c->corrects_position][] = (int) $c->position;
                }
                $lifecycles = [];
                foreach ($positions as $p) {
                    $lifecycles[$p] = FindingLifecycle::of($p, $notices[$p] ?? [], $corrections[$p] ?? []);
                }

                return $lifecycles;
            }

            public function appendNotice(GovernanceNotice $notice): int
            {
                [$position, $recordedAt] = $this->next();
                $this->db->insert("INSERT INTO refgeo_publications (position, kind, notice_kind, concerns_position, merge_basis, ended_by, confirmed_by, confirmed_digest, roster_version, governance_rule, recorded_at)
                    VALUES (?, 'notice', ?, ?, ?::jsonb, ?, ?, ?, ?, ?, ?::timestamptz)", [
                    $position, $notice->kind->value, $notice->concerns,
                    $notice->kind === NoticeKind::Finding ? json_encode($notice->mergeBasis, JSON_THROW_ON_ERROR) : null,
                    $notice->endedBy, $notice->confirmedBy?->value(), $notice->digest, $notice->rosterVersion, self::NOTICE_RULE, $recordedAt,
                ]);

                return $position;
            }

            /**
             * The next position and the clock reading for it (G-4 clock rule: refused, never adjusted).
             *
             * @return array{int, string}
             * @throws ClockNotPastBoundary
             */
            private function next(): array
            {
                $clock = $this->db->selectOne("SELECT x.c::text AS c, (x.c > coalesce(s.closed_through_time, '-infinity') AND x.c >= coalesce(s.last_recorded_at, '-infinity')) AS past, s.last_position
                    FROM (SELECT {$this->clockSql} AS c) x, refgeo_sequence s WHERE s.id = 1");
                if (!$clock->past) {
                    throw new ClockNotPastBoundary();
                }

                return [(int) $clock->last_position + 1, $clock->c];
            }
        };
    }
}
