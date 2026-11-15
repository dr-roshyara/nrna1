<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application\Ports;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * I-TDD-02 §7 R-1, as I-2 needs it: the signed-in session's own R in organisation O. The SEAM of plan step I-2: the
 * real answer comes from bindings (I-3) and R-1 (I-4). Until then production binds NoActingRefYet, which refuses
 * (fail closed); tests use doubles. Never answered from e-mail or the legacy user-organisation tables (D-160).
 */
interface SessionActorRefs
{
    /**
     * @return OrganisationPersonRef|null null: the session holds no R in O (`NotBound`)
     * @throws IdentityUnavailable Identity cannot answer (no fallback)
     */
    public function actingRef(TenantId $organisation): ?OrganisationPersonRef;
}
