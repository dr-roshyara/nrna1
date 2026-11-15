<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * I-TDD-02 §10.1, the correspondence checks (PI-17a). Identity checks that the answer belongs to THIS call; whether the
 * officer is competent is Governance's answer and is never re-evaluated here. Single use is checked by the claim store
 * in the claim operation's transaction. Integrity beyond the process boundary is PI-17b (an assessment), not this.
 */
final class AuthorityEvidenceCheck
{
    /** @param array<string, string> $subject the call's own subject identifiers */
    public function refusal(
        AuthorityEvidence $evidence,
        TenantId $organisation,
        OrganisationPersonRef $sessionActor,
        string $actKind,
        array $subject,
        \DateTimeImmutable $requestStart,
        \DateTimeImmutable $now,
    ): ?ClaimRefusal {
        ksort($subject);
        $given = $evidence->subject;
        ksort($given);

        return match (true) {
            !$evidence->organisation->equals($organisation) => ClaimRefusal::EvidenceOrganisation,
            !$evidence->actor->equals($sessionActor) => ClaimRefusal::EvidenceActor,      // decisive: Identity knows who is signed in
            $evidence->actKind !== $actKind => ClaimRefusal::EvidenceAct,
            $given !== $subject => ClaimRefusal::EvidenceSubject,
            $evidence->outcome !== 'authorised' => ClaimRefusal::EvidenceOutcome,          // NotAuthorised / Undetermined never pass
            $evidence->evaluatedFor !== $requestStart->format('Y-m-d') => ClaimRefusal::EvidenceDate,
            $evidence->checkedAt < $requestStart || $evidence->checkedAt > $now => ClaimRefusal::EvidenceStale,
            default => null,
        };
    }
}
