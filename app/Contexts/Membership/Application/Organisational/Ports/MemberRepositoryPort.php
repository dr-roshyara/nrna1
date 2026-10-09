<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Persistence port for the Member aggregate (Slice 1a contract §3–§4).
 *
 * - Every operation is scoped by an explicit TenantId (R-1a-5).
 * - Facts are only appended, never changed or removed (R-1a-4).
 * - An accepted decision is appended only at the version it was decided on;
 *   its seq is basedOnVersion + 1 (R-1a-1).
 * - recordedAt is assigned here and never decreases along seq (R-1a-3).
 * - The idempotency key check is atomic with the append (IDEM-3).
 */
interface MemberRepositoryPort
{
    public function load(TenantId $tenant, MemberId $id): ?Member;

    public function findByHolder(TenantId $tenant, PersonRef $holder): ?Member;

    /** Atomic: at most one Member per (tenant, holder) (INV-M). */
    public function create(TenantId $tenant, MemberId $id, PersonRef $holder): CreateResult;

    public function appliedCommand(TenantId $tenant, IdempotencyKey $key): ?AppliedCommand;

    /** @throws \InvalidArgumentException when the decision was refused */
    public function append(TenantId $tenant, MemberId $id, Decision $accepted, IdempotencyKey $key, string $fingerprint): AppendResult;
}
