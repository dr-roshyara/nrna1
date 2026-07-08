<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application;

use App\Contexts\Contestation\Application\Exception\AwaitingAdjudication;
use App\Contexts\Contestation\Application\Exception\DeterminationAlreadyApplied;
use App\Contexts\Contestation\Application\Inbox\ChallengeReactionInboxTranslator;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Challenge\DeterminationOutcome;
use App\Contexts\Contestation\Domain\Challenge\Exception\ConflictingDetermination;
use App\Contexts\Contestation\Domain\Challenge\Exception\IllegalChallengeTransition;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Domain\Shared\Clock\ClockInterface;

/**
 * Inbox consumer: Contestation reacts to `DeterminationIssued` by adjudicating the
 * Challenge. Thin — it reconstructs local identities (ADR-T16), applies the application
 * timestamp (injected clock), delegates the business decision to
 * {@see ChallengeAdjudicationReaction}, and TRANSLATES any business condition into a
 * Messaging Platform marker via {@see ChallengeReactionInboxTranslator}. The messaging
 * vocabulary lives only in the translator.
 */
final class AdjudicateChallengeHandler implements InboxHandler
{
    public function __construct(
        private readonly ChallengeAdjudicationReaction $reaction,
        private readonly ClockInterface $clock,
        private readonly ChallengeReactionInboxTranslator $translator,
    ) {
    }

    public function consumerContext(): string
    {
        return 'Contestation';
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
        $challengeId = ChallengeId::fromString($this->stringField($message->payload, 'challengeRef'));
        $determinationId = DeterminationId::fromString($this->stringField($message->payload, 'determinationId'));
        $outcome = DeterminationOutcome::from($this->stringField($message->payload, 'outcome'));

        try {
            $this->reaction->on($challengeId, $determinationId, $outcome, $this->clock->now());
        } catch (ConflictingDetermination|DeterminationAlreadyApplied|AwaitingAdjudication|IllegalChallengeTransition $businessCondition) {
            throw $this->translator->toInboxOutcome($businessCondition);
        }
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
