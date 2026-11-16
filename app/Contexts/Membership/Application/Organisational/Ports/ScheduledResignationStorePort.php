<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignation;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Persistence port for scheduled resignation declarations (PBDIGIT-354 S2-4b; D-204; M-TDD-01 §5.2).
 *
 * - Held **outside the ledger**, per membership period: nothing written here is a Membership fact; C1 / C2 never read
 *   it (MI-4).
 * - Every operation is scoped by an explicit TenantId (R-1a-5).
 * - **Nothing is deleted, and a declaration changes only from `scheduled`, once** (compare-and-set): its declared
 *   fields are never changed, so the history of declaration, withdrawal and execution is kept.
 * - **At most one `scheduled` declaration per period**, enforced atomically by the store.
 * - The idempotency key check is atomic with the write (IDEM-3). The keys are this store's own, separate from the
 *   ledger's command keys.
 */
interface ScheduledResignationStorePort
{
    /** @return list<ScheduledResignation> the member's declarations, oldest first, in every state */
    public function forMember(TenantId $tenant, MemberId $member): array;

    public function find(TenantId $tenant, MemberId $member, DeclarationId $id): ?ScheduledResignation;

    public function appliedRequest(TenantId $tenant, IdempotencyKey $key): ?AppliedDeclarationRequest;

    /**
     * Record a new `scheduled` declaration, decided on the membership at $basedOnVersion. A changed version, another
     * pending declaration for the period or a taken id → Conflict; nothing written.
     */
    public function record(TenantId $tenant, MemberId $member, int $basedOnVersion, ScheduledResignation $scheduled, IdempotencyKey $key, string $fingerprint): DeclarationWriteOutcome;

    /**
     * S2-4d (M-TDD-01 §5.2; D-239): withdraw, DECIDED AT LEDGER TIME. Under the organisation's ledger-clock lock (the
     * same lock the closing step takes), with ONE clock reading, the store asks the OrganisationCalendar for the
     * organisation's date at that reading and lets the declaration decide
     * (`ScheduledResignation::withdraw($declaredOn, $by, $organisationToday)`), then writes by compare-and-set.
     * Returns the domain's refusal unchanged (nothing written, no key). Without a calendar it cannot answer: it throws
     * (fail closed).
     */
    public function withdraw(TenantId $tenant, MemberId $member, DeclarationId $id, TimePoint $declaredOn, Provenance $by, IdempotencyKey $key, string $fingerprint): DeclarationWriteOutcome|RefusalReason;

    /**
     * Store a transition FROM `scheduled` (superseded or executed, by the closing step): written only if the stored
     * declaration is still `scheduled` (compare-and-set, MI-2), else Conflict.
     */
    public function transition(TenantId $tenant, MemberId $member, ScheduledResignation $changed, IdempotencyKey $key, string $fingerprint): DeclarationWriteOutcome;
}
