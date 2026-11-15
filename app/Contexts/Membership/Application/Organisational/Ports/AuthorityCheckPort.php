<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Consumer-side port: may this actor perform this membership act on this subject at this date?
 * (Slice 1a contract §6; aligned with the accepted AuthorityCheck, D-208 §1–§2 and C-1a, by PBDIGIT-354 S2-3a, D-238.)
 *
 * Competence is Governance's (DS-4). Membership only records the returned evidence, basis included, on the fact. It
 * is asked again on every attempt (R-1a-2). No production implementation is bound until Governance S3 (G-3).
 */
interface AuthorityCheckPort
{
    public function check(TenantId $tenant, AuthorityRequest $request): AuthorityDecision;
}
