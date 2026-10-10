<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Contexts\Membership\Application\Organisational\Appended;
use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\AppendResult;
use App\Contexts\Membership\Application\Organisational\Ports\AppliedCommand;
use App\Contexts\Membership\Application\Organisational\Ports\CreateResult;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Application\Organisational\Ports\StorableValidTime;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\RecordedFact;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * In-memory reference adapter for MemberRepositoryPort (Slice 1a). Each call is
 * atomic (single-threaded PHP), which stands in for the database transaction
 * and constraints of Slice 1b.
 */
final class InMemoryMemberRepository implements MemberRepositoryPort
{
    /** @var array<string, array<string, array{holder: PersonRef, log: list<RecordedFact>}>> */
    private array $members = [];
    /** @var array<string, array<string, string>> tenant => holder => member id */
    private array $holders = [];
    /** @var array<string, array<string, AppliedCommand>> */
    private array $applied = [];
    public int $appendCalls = 0;

    /** @param \Closure(): int $clock */
    public function __construct(private readonly \Closure $clock)
    {
    }

    public function load(TenantId $tenant, MemberId $id): ?Member
    {
        $row = $this->members[$tenant->value()][$id->value()] ?? null;

        return $row === null ? null : Member::fromLog($id, $row['holder'], $tenant, ...$row['log']);
    }

    public function findByHolder(TenantId $tenant, PersonRef $holder): ?Member
    {
        $id = $this->holders[$tenant->value()][$holder->value()] ?? null;

        return $id === null ? null : $this->load($tenant, MemberId::fromString($id));
    }

    public function create(TenantId $tenant, MemberId $id, PersonRef $holder): CreateResult
    {
        $existing = $this->holders[$tenant->value()][$holder->value()] ?? null;
        if ($existing !== null) {
            return CreateResult::holderExists(MemberId::fromString($existing));
        }
        if (isset($this->members[$tenant->value()][$id->value()])) {
            // F-3 (a): an id collision with another holder is a caller defect: an infrastructure
            // failure with no state change, as PostgreSQL's primary-key violation (also a RuntimeException).
            throw new \RuntimeException("MemberId '{$id->value()}' already belongs to another holder in this tenant.");
        }
        $this->members[$tenant->value()][$id->value()] = ['holder' => $holder, 'log' => []];
        $this->holders[$tenant->value()][$holder->value()] = $id->value();

        return CreateResult::created(Member::fromLog($id, $holder, $tenant));
    }

    public function appliedCommand(TenantId $tenant, IdempotencyKey $key): ?AppliedCommand
    {
        return $this->applied[$tenant->value()][$key->value()] ?? null;
    }

    public function append(TenantId $tenant, MemberId $id, Decision $accepted, IdempotencyKey $key, string $fingerprint): AppendResult
    {
        $this->appendCalls++;
        if (!$accepted->isAccepted()) {
            throw new \InvalidArgumentException('Only accepted decisions can be appended.');
        }
        // F-3 (K-1): the key lookup precedes every other outcome-producing check (IDEM-1 / IDEM-3), as in PostgreSQL; the accepted-decision precondition above is call validation.
        $seen = $this->appliedCommand($tenant, $key);
        if ($seen !== null) {
            return $seen->fingerprint() === $fingerprint
                ? AppendResult::retried($seen->appended())
                : AppendResult::failed(AppendOutcome::IdempotencyKeyConflict);
        }
        // R-1a-6: after the key, before the member / version step.
        if (!StorableValidTime::admits($accepted->fact())) {
            return AppendResult::failed(AppendOutcome::ValidTimeNotStorable);
        }
        // The member / version step (PostgreSQL: one compare-and-set; here: member, then version).
        if (!isset($this->members[$tenant->value()][$id->value()])) {
            return AppendResult::failed(AppendOutcome::MemberNotFound);
        }

        return $this->record($tenant, $id, $accepted, $key, $fingerprint);
    }

    /**
     * TEST-ONLY (Option E, E4-L): writes a record as an adapter version before R-1a-6 would have,
     * i.e. append() without the storable-valid-time check. It models legacy state only; no
     * production adapter offers such a path, and new writes through append() cannot create it.
     */
    public function seedPreOptionEAppend(TenantId $tenant, MemberId $id, Decision $accepted, IdempotencyKey $key, string $fingerprint): Appended
    {
        if (!$accepted->isAccepted() || !isset($this->members[$tenant->value()][$id->value()]) || $this->appliedCommand($tenant, $key) !== null) {
            throw new \LogicException('Seed an accepted decision for an existing Member under an unused key.');
        }

        return $this->record($tenant, $id, $accepted, $key, $fingerprint)->appended();
    }

    /** Version check, then the fact and the key row (one atomic step in this single-threaded double). */
    private function record(TenantId $tenant, MemberId $id, Decision $accepted, IdempotencyKey $key, string $fingerprint): AppendResult
    {
        $log = $this->members[$tenant->value()][$id->value()]['log'];
        if ($accepted->basedOnVersion() !== count($log)) {
            return AppendResult::failed(AppendOutcome::ConcurrencyConflict);
        }
        $now = ($this->clock)();
        $last = $log === [] ? null : end($log)->recordedAt()->ordinal();
        $recordedAt = Instant::of($last === null ? $now : max($now, $last));
        $seq = count($log) + 1;
        $this->members[$tenant->value()][$id->value()]['log'][] = RecordedFact::of($seq, $recordedAt, $accepted->fact());
        $appended = new Appended($id, $accepted->fact()->id(), $seq, $recordedAt);
        $this->applied[$tenant->value()][$key->value()] = new AppliedCommand($fingerprint, $appended);

        return AppendResult::success($appended);
    }
}
