<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\Appended;
use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\AppendResult;
use App\Contexts\Membership\Application\Organisational\Ports\AppliedCommand;
use App\Contexts\Membership\Application\Organisational\Ports\CreateResult;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Application\Organisational\Ports\StorableValidTime;
use App\Contexts\Membership\Domain\Organisational\Admit;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\End;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\Fact;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\RecordedFact;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;
use Illuminate\Database\UniqueConstraintViolationException;

/**
 * PostgreSQL adapter for MemberRepositoryPort (Slice 1b contract §5–§6; PBDIGIT-260).
 *
 * - PostgreSQL only (DB-1): fails fast on any other driver.
 * - Every query is filtered by the explicit tenant (no implicit scopes).
 * - Facts are only inserted (no update / delete path; no Eloquent model).
 * - append: one transaction: key check → storable valid time (R-1a-6) → version
 *   compare-and-set (row lock) → fact insert at seq = new version → key registration. It never retries; the
 *   application re-decides on ConcurrencyConflict (R-1a-2).
 * - Only the outcomes defined by the port are translated; other failures propagate.
 */
final class PostgresMemberRepository implements MemberRepositoryPort
{
    private const COMMANDS_PKEY = 'org_membership_commands_pkey';

    /** @param \Closure(): int $clock the current instant as epoch microseconds (UTC) */
    public function __construct(private readonly Connection $db, private readonly \Closure $clock)
    {
        if ($db->getDriverName() !== 'pgsql') {
            throw UnsupportedMembershipDatabaseDriver::for($db->getName() ?? '?', $db->getDriverName());
        }
    }

    public function load(TenantId $tenant, MemberId $id): ?Member
    {
        $row = $this->db->table('org_memberships')
            ->where('organisation_id', $tenant->value())
            ->where('id', $id->value())
            ->first();
        if ($row === null) {
            return null;
        }

        $facts = $this->db->table('org_membership_facts')
            ->select([
                'seq', 'fact_id', 'kind', 'initial_term', 'period_fact_id', 'end_kind', 'provenance',
                $this->db->raw('effective_at::text AS effective_date'),
                $this->db->raw('initial_term_until::text AS initial_term_until_date'),
                $this->db->raw(InstantMicros::selectExpression('decided_at') . ' AS decided_us'),
                $this->db->raw(InstantMicros::selectExpression('recorded_at') . ' AS recorded_us'),
            ])
            ->where('organisation_id', $tenant->value())
            ->where('membership_id', $id->value())
            ->orderBy('seq')
            ->get();

        $log = [];
        foreach ($facts as $f) {
            $log[] = RecordedFact::of((int) $f->seq, Instant::of((int) $f->recorded_us), $this->decode($f));
        }

        return Member::fromLog($id, PersonRef::fromString($row->person_ref), $tenant, ...$log);
    }

    public function findByHolder(TenantId $tenant, PersonRef $holder): ?Member
    {
        $id = $this->db->table('org_memberships')
            ->where('organisation_id', $tenant->value())
            ->where('person_ref', $holder->value())
            ->value('id');

        return $id === null ? null : $this->load($tenant, MemberId::fromString($id));
    }

    public function create(TenantId $tenant, MemberId $id, PersonRef $holder): CreateResult
    {
        $inserted = $this->db->selectOne(
            'INSERT INTO org_memberships (organisation_id, id, person_ref, version, created_at)
             VALUES (?, ?, ?, 0, now())
             ON CONFLICT (organisation_id, person_ref) DO NOTHING
             RETURNING id',
            [$tenant->value(), $id->value(), $holder->value()],
        );
        if ($inserted !== null) {
            return CreateResult::created(Member::fromLog($id, $holder, $tenant));
        }
        $existing = $this->db->table('org_memberships')
            ->where('organisation_id', $tenant->value())
            ->where('person_ref', $holder->value())
            ->value('id');

        return CreateResult::holderExists(MemberId::fromString($existing));
    }

    public function appliedCommand(TenantId $tenant, IdempotencyKey $key): ?AppliedCommand
    {
        $row = $this->db->table('org_membership_commands')
            ->select(['fingerprint', 'membership_id', 'fact_id', 'seq', $this->db->raw(InstantMicros::selectExpression('recorded_at') . ' AS recorded_us')])
            ->where('organisation_id', $tenant->value())
            ->where('idempotency_key', $key->value())
            ->first();

        return $row === null ? null : new AppliedCommand(
            $row->fingerprint,
            new Appended(MemberId::fromString($row->membership_id), FactId::fromString($row->fact_id), (int) $row->seq, Instant::of((int) $row->recorded_us)),
        );
    }

    public function append(TenantId $tenant, MemberId $id, Decision $accepted, IdempotencyKey $key, string $fingerprint): AppendResult
    {
        if (!$accepted->isAccepted()) {
            throw new \InvalidArgumentException('Only accepted decisions can be appended.');
        }

        try {
            return $this->db->transaction(fn (): AppendResult => $this->appendInTransaction($tenant, $id, $accepted, $key, $fingerprint));
        } catch (UniqueConstraintViolationException $e) {
            // The same key was registered concurrently (for another Member of the tenant): answer from the key row.
            if (!str_contains($e->getMessage(), self::COMMANDS_PKEY)) {
                throw $e;
            }

            return $this->answerFromKey($tenant, $key, $fingerprint) ?? throw $e;
        }
    }

    private function appendInTransaction(TenantId $tenant, MemberId $id, Decision $accepted, IdempotencyKey $key, string $fingerprint): AppendResult
    {
        $seen = $this->answerFromKey($tenant, $key, $fingerprint);
        if ($seen !== null) {
            return $seen;
        }
        // R-1a-6: before the compare-and-set, so no statement has modified state yet.
        if (!StorableValidTime::admits($accepted->fact())) {
            return AppendResult::failed(AppendOutcome::ValidTimeNotStorable);
        }

        $version = $this->db->selectOne(
            'UPDATE org_memberships SET version = version + 1
             WHERE organisation_id = ? AND id = ? AND version = ?
             RETURNING version',
            [$tenant->value(), $id->value(), $accepted->basedOnVersion()],
        );
        if ($version === null) {
            // IDEM-3: the compare-and-set may have waited for a concurrent writer's commit. Under READ
            // COMMITTED that writer's key row is now visible: if it was this same key, answer from it,
            // instead of a conflict that would make the application re-decide a command already applied.
            $seenAfterWait = $this->answerFromKey($tenant, $key, $fingerprint);
            if ($seenAfterWait !== null) {
                return $seenAfterWait;
            }
            $exists = $this->db->table('org_memberships')
                ->where('organisation_id', $tenant->value())->where('id', $id->value())->exists();

            return AppendResult::failed($exists ? AppendOutcome::ConcurrencyConflict : AppendOutcome::MemberNotFound);
        }
        $seq = (int) $version->version;

        $last = $this->db->table('org_membership_facts')
            ->where('organisation_id', $tenant->value())->where('membership_id', $id->value())
            ->max($this->db->raw(InstantMicros::selectExpression('recorded_at')));
        $recordedAt = Instant::of(max(($this->clock)(), $last === null ? PHP_INT_MIN : (int) $last));

        $fact = $accepted->fact();
        $this->db->table('org_membership_facts')->insert([
            'organisation_id' => $tenant->value(),
            'membership_id' => $id->value(),
            'seq' => $seq,
            'recorded_at' => InstantMicros::toTimestamp($recordedAt),
        ] + $this->encode($fact));

        $this->db->table('org_membership_commands')->insert([
            'organisation_id' => $tenant->value(),
            'idempotency_key' => $key->value(),
            'fingerprint' => $fingerprint,
            'membership_id' => $id->value(),
            'fact_id' => $fact->id()->value(),
            'seq' => $seq,
            'recorded_at' => InstantMicros::toTimestamp($recordedAt),
        ]);

        return AppendResult::success(new Appended($id, $fact->id(), $seq, $recordedAt));
    }

    private function answerFromKey(TenantId $tenant, IdempotencyKey $key, string $fingerprint): ?AppendResult
    {
        $seen = $this->appliedCommand($tenant, $key);
        if ($seen === null) {
            return null;
        }

        return $seen->fingerprint() === $fingerprint
            ? AppendResult::retried($seen->appended())
            : AppendResult::failed(AppendOutcome::IdempotencyKeyConflict);
    }

    /** @return array<string, mixed> */
    private function encode(Fact $fact): array
    {
        $common = [
            'fact_id' => $fact->id()->value(),
            'effective_at' => DateOrdinal::toDate($fact->effectiveAt()),
            'decided_at' => $fact->decidedAt() === null ? null : InstantMicros::toTimestamp($fact->decidedAt()),
            'provenance' => ProvenanceJson::encode($fact->provenance()),
        ];

        if ($fact instanceof Admit) {
            $until = $fact->initialTermUntil();

            return $common + [
                'kind' => 'admit',
                'initial_term' => $until === null ? 'none' : ($until->isInfinite() ? 'unlimited' : 'until'),
                'initial_term_until' => $until === null || $until->isInfinite() ? null : DateOrdinal::toDate($until),
                'period_fact_id' => null,
                'end_kind' => null,
            ];
        }
        if ($fact instanceof End) {
            return $common + [
                'kind' => 'end',
                'initial_term' => null,
                'initial_term_until' => null,
                'period_fact_id' => $fact->period()->value(),
                'end_kind' => $fact->kind()->value(),
            ];
        }

        throw new \LogicException('Unsupported fact kind for Slice 1b: ' . $fact::class);
    }

    private function decode(object $f): Fact
    {
        $decidedAt = $f->decided_us === null ? null : Instant::of((int) $f->decided_us);
        $provenance = ProvenanceJson::decode($f->provenance);
        $effective = DateOrdinal::fromDate($f->effective_date);

        return match ($f->kind) {
            'admit' => new Admit(
                FactId::fromString($f->fact_id),
                $effective,
                match ($f->initial_term) {
                    'none' => null,
                    'unlimited' => TimePoint::infinity(),
                    'until' => DateOrdinal::fromDate($f->initial_term_until_date),
                },
                $provenance,
                $decidedAt,
            ),
            'end' => new End(
                FactId::fromString($f->fact_id),
                PeriodId::fromString($f->period_fact_id),
                $effective,
                EndKind::fromString($f->end_kind),
                $provenance,
                $decidedAt,
            ),
        };
    }
}
