<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Identity\Application\Ports\ClaimCodes;
use App\Contexts\Identity\Application\Ports\ClaimStore;
use App\Contexts\Identity\Application\Ports\RequestTime;
use App\Contexts\Identity\Application\Ports\SessionActorRefs;
use App\Contexts\Identity\Domain\ClaimWindow;

/**
 * I-TDD-02 §6.2 ReplaceClaim (A1): one act withdraws an ACTIVE ordinary claim and gives a new one for the same R, in
 * one transaction (old → revoked, `replacesClaimId` recorded). An expired claim is not replaced: O issues a new claim
 * instead (R8-03, PI-27). Authority is evaluated at this act (D-12): having issued the old claim confers nothing.
 */
final class ReplaceClaim
{
    public function __construct(
        private readonly SessionActorRefs $actors,
        private readonly ClaimStore $store,
        private readonly ClaimCodes $codes,
        private readonly RequestTime $time,
        private readonly AuthorityEvidenceCheck $check,
    ) {
    }

    /** @throws IdentityUnavailable */
    public function replace(ReplaceClaimCommand $command): ClaimOutcome
    {
        $o = $command->organisation;
        $actor = $this->actors->actingRef($o);
        if ($actor === null) {
            return $this->refuse($command, ClaimRefusal::NotActing);
        }
        if (!ClaimIds::isWellFormed($command->oldClaimId)) {
            return $this->refuse($command, ClaimRefusal::UnknownClaim);
        }
        $subject = ['claimId' => $command->oldClaimId, 'newIssuanceId' => $command->newIssuanceId];
        $why = $this->check->refusal($command->evidence, $o, $actor, 'manage-claims', $subject, $this->time->requestStart(), $this->time->currentInstant());
        if ($why !== null) {
            return $this->refuse($command, $why);
        }

        return $this->store->replace($o, $command->oldClaimId, $command->newIssuanceId, $this->codes->generate(),
            ClaimWindow::fromRules($command->requestedWindowDays), $command->evidence->requestId);
    }

    private function refuse(ReplaceClaimCommand $command, ClaimRefusal $reason): ClaimOutcome
    {
        $this->store->recordRefusal($command->organisation, ClaimIds::isWellFormed($command->oldClaimId) ? $command->oldClaimId : null, $reason);

        return ClaimOutcome::refused($reason);
    }
}
