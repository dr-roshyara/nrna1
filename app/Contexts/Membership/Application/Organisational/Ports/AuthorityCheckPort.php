<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Consumer-side port: may this actor perform this membership act? (Slice 1a contract §6)
 *
 * Competence is Governance's (DS-4). Membership only records the returned
 * evidence on the fact. It is asked again on every attempt (R-1a-2).
 */
interface AuthorityCheckPort
{
    public function check(TenantId $tenant, string $actor, MembershipAct $act): AuthorityDecision;
}
