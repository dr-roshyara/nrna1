<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application;

use App\Contexts\Election\Application\Port\ReactionEventOutbox;
use App\Contexts\Election\Domain\DeterminationId;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\Exception\CannotApplyDeterminationToUnknownElection;
use App\Contexts\Election\Domain\Exception\DeterminationLacksElectionScope;
use App\Contexts\Election\Domain\Repository\ElectionRepository;
use App\Contexts\Election\Domain\RulingOutcome;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use App\Domain\Shared\Clock\ClockInterface;
use DateTimeImmutable;

/**
 * Election's inbox consumer for `DeterminationIssued` (payload schema version 2). It
 * REACTS: it reconstructs Election's local identities from the published payload
 * (ADR-T16), resolves the target election, and asks the aggregate to decide the
 * correction. The aggregate owns the rule; this handler owns the wiring.
 *
 * Business decisions (ARB round-2 rulings):
 *  - no election scope (schema v1) → {@see DeterminationLacksElectionScope} (business
 *    incompatibility; infrastructure later dead-letters it — never a silent drop);
 *  - unknown / out-of-organisation election → {@see CannotApplyDeterminationToUnknownElection}
 *    (never provisioned, never applied across organisation boundaries).
 *
 * Organisation scope lives at the repository boundary (the repository is resolved for
 * the ambient tenant), so the handler asks a purely domain question — keeping the
 * Election domain tenant-free (ADR-T16).
 */
final class DeterminationIssuedReactionHandler implements InboxHandler
{
    public function __construct(
        private readonly ElectionRepository $elections,
        private readonly ReactionEventOutbox $outbox,
        private readonly ClockInterface $clock,
    ) {
    }

    public function consumerContext(): string
    {
        return 'Election';
    }

    /**
     * @return list<string>
     */
    public function eventTypes(): array
    {
        return ['DeterminationIssued'];
    }

    public function handle(InboxMessage $message): void
    {
        $electionId = $this->electionScopeOf($message);
        $determinationId = DeterminationId::fromString($this->stringField($message->payload, 'determinationId'));
        $outcome = RulingOutcome::from($this->stringField($message->payload, 'outcome'));

        // The determination's ISSUANCE time. Parsed and available, but intentionally NOT
        // used as the correction's timestamp: `appliedAt` records when THIS context applies
        // the correction, a distinct fact (ARB Q1 ruling). Retained for future latency /
        // audit / replay use — if ever surfaced it travels as its own attribute, never as
        // `appliedAt`.
        $determinationIssuedAt = new DateTimeImmutable($this->stringField($message->payload, 'occurredAt'));

        // Application timestamp: when the Election bounded context applies the correction.
        $appliedAt = $this->clock->now();

        $election = $this->elections->find($electionId);
        if ($election === null) {
            throw CannotApplyDeterminationToUnknownElection::withId($electionId);
        }

        $election->applyDetermination($determinationId, $outcome, $appliedAt);
        $this->outbox->enqueue(
            EventProvenance::fromConsumed($message->correlationId, $message->eventId),
            ...$election->pullEvents(),
        );
        $this->elections->save($election);
    }

    /**
     * Reconstruct Election's local ElectionId from the payload's contested-outcome
     * scope. Its absence (a schema-version-1 payload) is a business incompatibility.
     */
    private function electionScopeOf(InboxMessage $message): ElectionId
    {
        $contestedOutcome = $message->payload['contestedOutcome'] ?? null;
        $electionId = is_array($contestedOutcome) ? ($contestedOutcome['electionId'] ?? null) : null;
        if (!is_scalar($electionId)) {
            throw DeterminationLacksElectionScope::forEvent($message->eventId);
        }

        return ElectionId::fromString((string) $electionId);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function stringField(array $payload, string $key): string
    {
        $value = $payload[$key] ?? null;

        return is_scalar($value) ? (string) $value : '';
    }
}
