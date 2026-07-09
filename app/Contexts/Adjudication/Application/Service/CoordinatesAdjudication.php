<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Service;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Application\Port\EventOutbox;
use App\Contexts\Adjudication\Application\Port\IdentityGenerator;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use App\Contexts\Adjudication\Domain\Determination\Determination;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Exception\DeterminationAlreadyIssued;
use App\Contexts\Adjudication\Domain\Repository\DeterminationRepository;

/**
 * Application coordinator (orchestration only — ADR-T14, plan R4).
 *
 * Determination is the sole aggregate written; Challenge is never touched here
 * (it resolves asynchronously later by reacting to DeterminationIssued +
 * ElectionCorrectionApplied). Event flow: aggregate records → pullEvents() →
 * EventOutbox.enqueue() within the same transaction → commit. This service
 * performs NO business reasoning (legitimacy/admissibility are decided upstream).
 *
 * NOTE: the real adapter wraps save() + enqueue() in one DB transaction (the
 * service owns that boundary); the in-memory test doubles execute them inline.
 */
final class CoordinatesAdjudication implements AdjudicationService
{
    public function __construct(
        private readonly DeterminationRepository $determinations,
        private readonly EventOutbox $outbox,
        private readonly IdentityGenerator $identities,
    ) {
    }

    public function issueDetermination(IssueDeterminationCommand $command): DeterminationId
    {
        // Precondition (business decision made HERE, not in the repository):
        // logical uniqueness — one determination per challenge.
        if ($this->determinations->findByChallengeRef($command->challengeRef) !== null) {
            throw DeterminationAlreadyIssued::forChallenge($command->challengeRef);
        }

        $id = $this->determinations->nextIdentity();

        $determination = Determination::prepare(
            $id,
            $command->challengeRef,
            $command->issuedByAuthority,
            $command->jurisdiction,
            $command->evidenceEnvelopeRef,
            $command->contestedOutcome,
        );
        $determination->issue(
            $command->outcome,
            $command->legitimacy,
            $command->reason,
            $command->occurredAt,
        );

        $this->determinations->save($determination);
        // Chain start (raise path not yet implemented): mint the loop's correlation here.
        // When ChallengeRouted consumption lands, this becomes EventProvenance::fromConsumed.
        $this->outbox->enqueue(
            EventProvenance::start($this->identities->next()),
            ...$determination->pullEvents(),
        );

        return $id;
    }
}
