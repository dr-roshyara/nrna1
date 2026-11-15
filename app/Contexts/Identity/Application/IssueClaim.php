<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Identity\Application\Ports\ClaimCodes;
use App\Contexts\Identity\Application\Ports\ClaimStore;
use App\Contexts\Identity\Application\Ports\IssuedReferences;
use App\Contexts\Identity\Application\Ports\RequestTime;
use App\Contexts\Identity\Application\Ports\SessionActorRefs;
use App\Contexts\Identity\Domain\ClaimWindow;

/**
 * I-TDD-02 §6.2 IssueClaim (A1, D-196): an empowered officer of organisation O invites the person recorded as E to
 * bind their login to O's record. The claim grants NOTHING: no membership, office, vote or access.
 * Order: the session's own R (R-1 seam) → E's R → the Governance answer corresponds (§10.1) → the store
 * (idempotency, one active claim, R unbound, single use; all under the ClaimSlot lock, §6.2).
 * Refusals give one neutral message (ClaimRefusal::publicMessage); the reason goes to the content-free audit.
 */
final class IssueClaim
{
    public function __construct(
        private readonly SessionActorRefs $actors,
        private readonly IssuedReferences $references,
        private readonly ClaimStore $store,
        private readonly ClaimCodes $codes,
        private readonly RequestTime $time,
        private readonly AuthorityEvidenceCheck $check,
    ) {
    }

    /** @throws IdentityUnavailable Identity cannot answer: the act is refused, never guessed */
    public function issue(IssueClaimCommand $command): ClaimOutcome
    {
        $o = $command->organisation;
        $actor = $this->actors->actingRef($o);
        if ($actor === null) {
            return $this->refuse($command, ClaimRefusal::NotActing);
        }
        $ref = $this->references->find($o, $command->entry);
        if ($ref === null) {
            return $this->refuse($command, ClaimRefusal::UnknownEntry);
        }
        $subject = ['entryRef' => $command->entry->value(), 'personRef' => $ref->value(), 'issuanceId' => $command->issuanceId];
        $why = $this->check->refusal($command->evidence, $o, $actor, 'manage-claims', $subject, $this->time->requestStart(), $this->time->currentInstant());
        if ($why !== null) {
            return $this->refuse($command, $why);
        }

        return $this->store->issue($o, $ref, $command->issuanceId, $this->codes->generate(),
            ClaimWindow::fromRules($command->requestedWindowDays), $command->evidence->requestId);
    }

    private function refuse(IssueClaimCommand $command, ClaimRefusal $reason): ClaimOutcome
    {
        $this->store->recordRefusal($command->organisation, null, $reason);

        return ClaimOutcome::refused($reason);
    }
}
