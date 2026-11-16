<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppliedDeclarationRequest;
use App\Contexts\Membership\Application\Organisational\Ports\DeclarationWriteOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\OrganisationCalendar;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Application\Organisational\Ports\ScheduledResignationStorePort;
use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignation;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignationState;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;
use Illuminate\Database\UniqueConstraintViolationException;

/**
 * PostgreSQL ScheduledResignationStorePort (PBDIGIT-354 S2-4b; D-204; M-TDD-01 §5.2). Tables:
 * `org_membership_scheduled_resignations`, `org_membership_declaration_commands` (migration 360000 / organisation 300700).
 *
 * - **record:** in one transaction: key check → the membership row FOR UPDATE and its version compared (the same row
 *   every ledger append updates, so a declaration is never decided on a stale membership) → insert → key row. A
 *   unique violation (the id, or a second `scheduled` declaration for the period: index ..._one_pending) → Conflict.
 * - **transition:** `UPDATE … WHERE state = 'scheduled'` (compare-and-set, MI-2): 0 rows → Conflict. The database
 *   trigger also refuses changing a declared field or a final declaration, and any DELETE.
 * - READ COMMITTED (PostgreSQL's default): a writer that waited re-reads the key, as PostgresMemberRepository (IDEM-3).
 * - `recorded_at` / `changed_at` are the database clock (`clock_timestamp()`, D-209's one clock). They are not ledger
 *   record times: a declaration is not a fact.
 */
final class PostgresScheduledResignationStore implements ScheduledResignationStorePort
{
    private const TABLE = 'org_membership_scheduled_resignations';
    private const COMMANDS = 'org_membership_declaration_commands';
    private const COMMANDS_PKEY = 'org_membership_declaration_commands_pkey';

    /**
     * @param ?LedgerClockReader    $clock    S2-4d: withdraw() takes its one reading under the ledger-clock lock
     * @param ?OrganisationCalendar $calendar S2-4d (D-239): the organisation's date of that reading. Without both,
     *                                        withdraw() refuses to act (fail closed); the closing step needs neither
     */
    public function __construct(
        private readonly Connection $db,
        private readonly ?LedgerClockReader $clock = null,
        private readonly ?OrganisationCalendar $calendar = null,
    ) {
    }

    public function forMember(TenantId $tenant, MemberId $member): array
    {
        return array_map(fn (object $r): ScheduledResignation => $this->decode($r), $this->select($tenant, $member)->orderBy('recorded_at')->orderBy('declaration_id')->get()->all());
    }

    public function find(TenantId $tenant, MemberId $member, DeclarationId $id): ?ScheduledResignation
    {
        $row = $this->select($tenant, $member)->where('declaration_id', $id->value())->first();

        return $row === null ? null : $this->decode($row);
    }

    /**
     * S2-4c, inside the ledger close only: the `scheduled` declarations with effective date ≤ $localDate, locked
     * (FOR UPDATE), oldest date first. A concurrent withdrawal then waits for the close and loses its compare-and-set
     * (MI-2); one that committed first is not returned.
     *
     * @return list<array{0: MemberId, 1: ScheduledResignation}>
     */
    public function lockDue(TenantId $tenant, TimePoint $localDate): array
    {
        if ($this->db->transactionLevel() === 0) {
            throw new \LogicException('lockDue() runs only inside the ledger close transaction.');
        }
        $rows = $this->db->table(self::TABLE)
            ->select([
                'membership_id', 'declaration_id', 'period_fact_id', 'provenance', 'state', 'withdrawn_by', 'executed_fact_id', 'superseded_because',
                $this->db->raw('declaration_date::text AS declaration_day'),
                $this->db->raw('effective_date::text AS effective_day'),
                $this->db->raw('withdrawn_on::text AS withdrawn_day'),
                $this->db->raw(InstantMicros::selectExpression('decided_at') . ' AS decided_us'),
            ])
            ->where('organisation_id', $tenant->value())
            ->where('state', ScheduledResignationState::Scheduled->value)
            ->where('effective_date', '<=', DateOrdinal::toDate($localDate))
            ->orderBy('effective_date')->orderBy('membership_id')->orderBy('declaration_id')
            ->lockForUpdate()
            ->get();

        return array_map(fn (object $r): array => [MemberId::fromString($r->membership_id), $this->decode($r)], $rows->all());
    }

    public function appliedRequest(TenantId $tenant, IdempotencyKey $key): ?AppliedDeclarationRequest
    {
        $row = $this->db->table(self::COMMANDS)->where('organisation_id', $tenant->value())->where('idempotency_key', $key->value())->first();

        return $row === null ? null : new AppliedDeclarationRequest($row->fingerprint, MemberId::fromString($row->membership_id), DeclarationId::fromString($row->declaration_id));
    }

    public function record(TenantId $tenant, MemberId $member, int $basedOnVersion, ScheduledResignation $scheduled, IdempotencyKey $key, string $fingerprint): DeclarationWriteOutcome
    {
        if ($scheduled->state() !== ScheduledResignationState::Scheduled) {
            throw new \InvalidArgumentException('Only a scheduled declaration is recorded; a change of state is a transition.');
        }

        return $this->guarded($tenant, $key, $fingerprint, function () use ($tenant, $member, $basedOnVersion, $scheduled, $key, $fingerprint): DeclarationWriteOutcome {
            $seen = $this->keyAnswer($tenant, $key, $fingerprint);
            if ($seen !== null) {
                return $seen;
            }
            $current = $this->db->selectOne('SELECT version FROM org_memberships WHERE organisation_id = ? AND id = ? FOR UPDATE', [$tenant->value(), $member->value()]);
            if ($current === null) {
                return DeclarationWriteOutcome::MemberNotFound;
            }
            if ((int) $current->version !== $basedOnVersion) {
                return $this->keyAnswer($tenant, $key, $fingerprint) ?? DeclarationWriteOutcome::Conflict;
            }
            $this->db->insert(
                'INSERT INTO ' . self::TABLE . ' (organisation_id, membership_id, declaration_id, period_fact_id, declaration_date, effective_date, provenance, decided_at, recorded_at, state)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, clock_timestamp(), ?)',
                [$tenant->value(), $member->value(), $scheduled->id()->value(), $scheduled->period()->value(), DateOrdinal::toDate($scheduled->declarationDate()),
                    DateOrdinal::toDate($scheduled->effectiveDate()), ProvenanceJson::encode($scheduled->provenance()),
                    $scheduled->decidedAt() === null ? null : InstantMicros::toTimestamp($scheduled->decidedAt()), $scheduled->state()->value],
            );
            $this->registerKey($tenant, $member, $scheduled->id(), $key, $fingerprint);

            return DeclarationWriteOutcome::Written;
        });
    }

    public function transition(TenantId $tenant, MemberId $member, ScheduledResignation $changed, IdempotencyKey $key, string $fingerprint): DeclarationWriteOutcome
    {
        if ($changed->state() === ScheduledResignationState::Scheduled) {
            throw new \InvalidArgumentException('A transition leaves the scheduled state.');
        }

        return $this->guarded($tenant, $key, $fingerprint, function () use ($tenant, $member, $changed, $key, $fingerprint): DeclarationWriteOutcome {
            return $this->keyAnswer($tenant, $key, $fingerprint) ?? $this->transitionRow($tenant, $member, $changed, $key, $fingerprint);
        });
    }

    /**
     * S2-4d (M-TDD-01 §5.2; D-239): withdraw, decided at ledger time. One transaction:
     * key check → the organisation's ledger-clock row FOR UPDATE (the lock the closing step and every append take,
     * so withdrawal and execution serialise on it) → ONE clock reading → the organisation's date of that reading →
     * the declaration row FOR UPDATE → `ScheduledResignation::withdraw($declaredOn, $by, $organisationToday)` →
     * compare-and-set. A refusal is returned unchanged; nothing is written.
     */
    public function withdraw(TenantId $tenant, MemberId $member, DeclarationId $id, TimePoint $declaredOn, Provenance $by, IdempotencyKey $key, string $fingerprint): DeclarationWriteOutcome|RefusalReason
    {
        if ($this->clock === null || $this->calendar === null) {
            throw new \LogicException("No organisation calendar: a withdrawal cannot be decided at the organisation's own date (D-239). Refused; nothing written (fail closed).");
        }

        return $this->guarded($tenant, $key, $fingerprint, function () use ($tenant, $member, $id, $declaredOn, $by, $key, $fingerprint): DeclarationWriteOutcome|RefusalReason {
            $seen = $this->keyAnswer($tenant, $key, $fingerprint);
            if ($seen !== null) {
                return $seen;
            }
            $this->db->selectOne('SELECT 1 AS locked FROM org_membership_ledger_clocks WHERE organisation_id = ? FOR UPDATE', [$tenant->value()])
                ?? throw new \RuntimeException("Ledger clock row missing for organisation {$tenant->value()} (fail closed).");
            $organisationToday = $this->calendar->localDateAt($tenant, $this->clock->now($this->db));
            $row = $this->select($tenant, $member)->where('declaration_id', $id->value())->lockForUpdate()->first();
            if ($row === null) {
                return DeclarationWriteOutcome::Conflict;
            }
            $withdrawn = $this->decode($row)->withdraw($declaredOn, $by, $organisationToday);
            if ($withdrawn instanceof RefusalReason) {
                return $withdrawn;
            }

            return $this->transitionRow($tenant, $member, $withdrawn, $key, $fingerprint);
        });
    }

    /** The compare-and-set write of a transition from `scheduled` and its key row; in the caller's transaction. */
    private function transitionRow(TenantId $tenant, MemberId $member, ScheduledResignation $changed, IdempotencyKey $key, string $fingerprint): DeclarationWriteOutcome
    {
        $changedRows = $this->db->update(
            'UPDATE ' . self::TABLE . "
             SET state = ?, withdrawn_on = ?, withdrawn_by = ?, executed_fact_id = ?, superseded_because = ?, changed_at = clock_timestamp()
             WHERE organisation_id = ? AND membership_id = ? AND declaration_id = ? AND state = 'scheduled'",
            [$changed->state()->value,
                $changed->withdrawnOn() === null ? null : DateOrdinal::toDate($changed->withdrawnOn()),
                $changed->withdrawnBy() === null ? null : ProvenanceJson::encode($changed->withdrawnBy()),
                $changed->executedAs()?->value(), $changed->supersededBecause(),
                $tenant->value(), $member->value(), $changed->id()->value()],
        );
        if ($changedRows !== 1) {
            // Compare-and-set lost (MI-2), or no such declaration. A writer we waited for may have been this key.
            return $this->keyAnswer($tenant, $key, $fingerprint) ?? DeclarationWriteOutcome::Conflict;
        }
        $this->registerKey($tenant, $member, $changed->id(), $key, $fingerprint);

        return DeclarationWriteOutcome::Written;
    }

    /** @param \Closure(): (DeclarationWriteOutcome|RefusalReason) $write */
    private function guarded(TenantId $tenant, IdempotencyKey $key, string $fingerprint, \Closure $write): DeclarationWriteOutcome|RefusalReason
    {
        try {
            return $this->db->transaction($write);
        } catch (UniqueConstraintViolationException $e) {
            // F-5: classified by SQLSTATE and the constraint on the driver's first error line, never by the message.
            if (PgUniqueViolation::isConstraint($e, self::COMMANDS_PKEY)) {
                return $this->keyAnswer($tenant, $key, $fingerprint) ?? throw $e;   // the same key, concurrently
            }
            if (PgUniqueViolation::isConstraint($e, self::TABLE . '_pkey') || PgUniqueViolation::isConstraint($e, self::TABLE . '_one_pending')) {
                return DeclarationWriteOutcome::Conflict;   // the id taken, or another pending declaration: decide again
            }
            throw $e;
        }
    }

    private function keyAnswer(TenantId $tenant, IdempotencyKey $key, string $fingerprint): ?DeclarationWriteOutcome
    {
        $seen = $this->appliedRequest($tenant, $key);
        if ($seen === null) {
            return null;
        }

        return $seen->fingerprint === $fingerprint ? DeclarationWriteOutcome::AlreadyApplied : DeclarationWriteOutcome::IdempotencyKeyConflict;
    }

    private function registerKey(TenantId $tenant, MemberId $member, DeclarationId $id, IdempotencyKey $key, string $fingerprint): void
    {
        $this->db->insert(
            'INSERT INTO ' . self::COMMANDS . ' (organisation_id, idempotency_key, fingerprint, membership_id, declaration_id, recorded_at) VALUES (?, ?, ?, ?, ?, clock_timestamp())',
            [$tenant->value(), $key->value(), $fingerprint, $member->value(), $id->value()],
        );
    }

    private function select(TenantId $tenant, MemberId $member): \Illuminate\Database\Query\Builder
    {
        return $this->db->table(self::TABLE)
            ->select([
                'declaration_id', 'period_fact_id', 'provenance', 'state', 'withdrawn_by', 'executed_fact_id', 'superseded_because',
                $this->db->raw('declaration_date::text AS declaration_day'),
                $this->db->raw('effective_date::text AS effective_day'),
                $this->db->raw('withdrawn_on::text AS withdrawn_day'),
                $this->db->raw(InstantMicros::selectExpression('decided_at') . ' AS decided_us'),
            ])
            ->where('organisation_id', $tenant->value())
            ->where('membership_id', $member->value());
    }

    private function decode(object $r): ScheduledResignation
    {
        try {
            return ScheduledResignation::fromStorage(
                DeclarationId::fromString($r->declaration_id),
                PeriodId::fromString($r->period_fact_id),
                DateOrdinal::fromDate($r->declaration_day),
                DateOrdinal::fromDate($r->effective_day),
                ProvenanceJson::decode($r->provenance),
                $r->decided_us === null ? null : Instant::of((int) $r->decided_us),
                ScheduledResignationState::from($r->state),
                $r->withdrawn_day === null ? null : DateOrdinal::fromDate($r->withdrawn_day),
                $r->withdrawn_by === null ? null : ProvenanceJson::decode($r->withdrawn_by),
                $r->executed_fact_id === null ? null : FactId::fromString($r->executed_fact_id),
                $r->superseded_because,
            );
        } catch (\InvalidArgumentException | \UnexpectedValueException | \JsonException | \ValueError $e) {
            // An inconsistent stored row is an infrastructure failure, never a valid declaration (fail closed).
            throw new \RuntimeException("Stored scheduled resignation {$r->declaration_id} is invalid: {$e->getMessage()}", 0, $e);
        }
    }
}
