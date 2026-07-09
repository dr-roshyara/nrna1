<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application;

use App\Contexts\Contestation\Application\Exception\AwaitingAdjudication;
use App\Contexts\Contestation\Application\Exception\DeterminationAlreadyApplied;
use App\Contexts\Contestation\Application\Inbox\ChallengeReactionOutcomeTranslator;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Challenge\Exception\IllegalChallengeTransition;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use App\Domain\Shared\Clock\ClockInterface;

/**
 * Inbox consumer: Contestation reacts to `ElectionCorrectionApplied` by resolving the
 * Challenge (operational completion). The correlation key is the `determinationId` (the
 * correction event carries no challengeId). Business conditions from
 * {@see ChallengeResolutionReaction} are translated to Messaging Platform markers — a
 * premature correction becomes a park (CausalPreconditionMissing).
 */
final class ResolveChallengeHandler implements InboxHandler
{
    public function __construct(
        private readonly ChallengeResolutionReaction $reaction,
        private readonly ClockInterface $clock,
        private readonly ChallengeReactionOutcomeTranslator $translator,
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
        return ['ElectionCorrectionApplied'];
    }

    public function handle(InboxMessage $message): void
    {
        $determinationId = DeterminationId::fromString($this->stringField($message->payload, 'determinationId'));

        try {
            $this->reaction->on(
                $determinationId,
                $this->clock->now(),
                EventProvenance::fromConsumed($message->correlationId, $message->eventId),
            );
        } catch (DeterminationAlreadyApplied|AwaitingAdjudication|IllegalChallengeTransition $businessCondition) {
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
