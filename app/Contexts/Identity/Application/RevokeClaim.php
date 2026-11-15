<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Identity\Application\Ports\ClaimStore;
use App\Contexts\Identity\Application\Ports\RequestTime;
use App\Contexts\Identity\Application\Ports\SessionActorRefs;

/**
 * I-TDD-02 §6.2 RevokeClaim (A1): withdraw O's invitation. Only an ACTIVE ordinary claim can be revoked: a redeemed
 * claim is terminal (INV-CLM-3), an expired one has nothing to withdraw (PI-27). Identity never revokes on its own;
 * against a leaked code it only throttles (D-197, I-3).
 */
final class RevokeClaim
{
    public function __construct(
        private readonly SessionActorRefs $actors,
        private readonly ClaimStore $store,
        private readonly RequestTime $time,
        private readonly AuthorityEvidenceCheck $check,
    ) {
    }

    /** @throws IdentityUnavailable */
    public function revoke(RevokeClaimCommand $command): ClaimOutcome
    {
        $o = $command->organisation;
        $actor = $this->actors->actingRef($o);
        if ($actor === null) {
            return $this->refuse($command, ClaimRefusal::NotActing);
        }
        if (!ClaimIds::isWellFormed($command->claimId)) {
            return $this->refuse($command, ClaimRefusal::UnknownClaim);
        }
        $why = $this->check->refusal($command->evidence, $o, $actor, 'manage-claims', ['claimId' => $command->claimId],
            $this->time->requestStart(), $this->time->currentInstant());
        if ($why !== null) {
            return $this->refuse($command, $why);
        }

        return $this->store->revoke($o, $command->claimId, $command->evidence->requestId);
    }

    private function refuse(RevokeClaimCommand $command, ClaimRefusal $reason): ClaimOutcome
    {
        $this->store->recordRefusal($command->organisation, ClaimIds::isWellFormed($command->claimId) ? $command->claimId : null, $reason);

        return ClaimOutcome::refused($reason);
    }
}
