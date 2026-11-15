<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppliedDeclarationRequest;
use App\Contexts\Membership\Application\Organisational\Ports\DeclarationWriteOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\ScheduledResignationStorePort;
use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignation;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignationState;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * In-memory ScheduledResignationStorePort (PBDIGIT-354 S2-4b) with the port's atomic rules: the membership's version
 * compare-and-set, one `scheduled` declaration per period, a taken id, transitions only from `scheduled`, and
 * idempotency. `conflictNext` injects that many Conflicts before any check, to test the retry.
 */
final class InMemoryScheduledResignationStore implements ScheduledResignationStorePort
{
    /** @var array<string, array<string, array<string, ScheduledResignation>>> tenant => member => declaration id => declaration */
    private array $rows = [];
    /** @var array<string, array<string, AppliedDeclarationRequest>> */
    private array $applied = [];
    public int $conflictNext = 0;

    public function __construct(private readonly InMemoryMemberRepository $members)
    {
    }

    public function forMember(TenantId $tenant, MemberId $member): array
    {
        return array_values($this->rows[$tenant->value()][$member->value()] ?? []);
    }

    public function find(TenantId $tenant, MemberId $member, DeclarationId $id): ?ScheduledResignation
    {
        return $this->rows[$tenant->value()][$member->value()][$id->value()] ?? null;
    }

    public function appliedRequest(TenantId $tenant, IdempotencyKey $key): ?AppliedDeclarationRequest
    {
        return $this->applied[$tenant->value()][$key->value()] ?? null;
    }

    public function record(TenantId $tenant, MemberId $member, int $basedOnVersion, ScheduledResignation $scheduled, IdempotencyKey $key, string $fingerprint): DeclarationWriteOutcome
    {
        if ($this->injectedConflict()) {
            return DeclarationWriteOutcome::Conflict;
        }
        $seen = $this->keyAnswer($tenant, $key, $fingerprint);
        if ($seen !== null) {
            return $seen;
        }
        $current = $this->members->load($tenant, $member);
        if ($current === null) {
            return DeclarationWriteOutcome::MemberNotFound;
        }
        if ($current->version() !== $basedOnVersion || $scheduled->state() !== ScheduledResignationState::Scheduled
            || $this->find($tenant, $member, $scheduled->id()) !== null) {
            return DeclarationWriteOutcome::Conflict;
        }
        foreach ($this->forMember($tenant, $member) as $other) {
            if ($other->period()->equals($scheduled->period()) && $other->state() === ScheduledResignationState::Scheduled) {
                return DeclarationWriteOutcome::Conflict;
            }
        }
        $this->rows[$tenant->value()][$member->value()][$scheduled->id()->value()] = $scheduled;
        $this->applied[$tenant->value()][$key->value()] = new AppliedDeclarationRequest($fingerprint, $member, $scheduled->id());

        return DeclarationWriteOutcome::Written;
    }

    public function transition(TenantId $tenant, MemberId $member, ScheduledResignation $changed, IdempotencyKey $key, string $fingerprint): DeclarationWriteOutcome
    {
        if ($this->injectedConflict()) {
            return DeclarationWriteOutcome::Conflict;
        }
        $seen = $this->keyAnswer($tenant, $key, $fingerprint);
        if ($seen !== null) {
            return $seen;
        }
        $stored = $this->find($tenant, $member, $changed->id());
        if ($stored === null || $stored->state() !== ScheduledResignationState::Scheduled || $changed->state() === ScheduledResignationState::Scheduled) {
            return DeclarationWriteOutcome::Conflict;
        }
        $this->rows[$tenant->value()][$member->value()][$changed->id()->value()] = $changed;
        $this->applied[$tenant->value()][$key->value()] = new AppliedDeclarationRequest($fingerprint, $member, $changed->id());

        return DeclarationWriteOutcome::Written;
    }

    private function injectedConflict(): bool
    {
        if ($this->conflictNext <= 0) {
            return false;
        }
        $this->conflictNext--;

        return true;
    }

    private function keyAnswer(TenantId $tenant, IdempotencyKey $key, string $fingerprint): ?DeclarationWriteOutcome
    {
        $seen = $this->appliedRequest($tenant, $key);
        if ($seen === null) {
            return null;
        }

        return $seen->fingerprint === $fingerprint ? DeclarationWriteOutcome::AlreadyApplied : DeclarationWriteOutcome::IdempotencyKeyConflict;
    }
}
