<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application;

use App\Contexts\Adjudication\Application\Process\AdjudicationProcessManager;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;

/**
 * Adjudication's inbox consumer for Contestation's published `ChallengeRouted` — the
 * WIRING of the constitutional correction loop's HEAD (ADR-T21 · EPIC-004K §3 PM-1).
 *
 * It owns nothing but the wiring: it reconstructs Adjudication's OWN `ChallengeRef`
 * from the payload's opaque identity string (ADR-T16 · contract R-4/R-9) and asks the
 * process manager to open the adjudication. PM-1 owns the rule, including its own
 * idempotency — a challenge already under adjudication opens no second process.
 *
 * WHAT IT DELIBERATELY DOES NOT DO (defended absences, not oversights):
 *  - **No outcome translator (G-2).** No application-owned outcome-translation
 *    responsibility exists in this scope. Duplicate delivery is the PLATFORM's seat
 *    (inbox dedupe on `(event_id, consumer_context)`, ADR-T3/T4), and a distinct
 *    message for an already-adjudicated challenge is PM-1's seat.
 *  - **No local `RoutedTo` value object.** `routedTo` is a string the producer
 *    records and no consumer needs — `openFor()` takes only the challenge identity.
 *    A VO for it would be a component without authority.
 *  - **No import of Contestation's `ChallengeRouted` class or its hydrator.** The
 *    hydrator is producer-side Infrastructure (contract R-2/R-3); importing it would
 *    create the codebase's first cross-context Infrastructure dependency and fail
 *    Deptrac. Only primitives cross.
 *  - **No provenance minting.** This slice publishes nothing; when Adjudication does
 *    publish it continues the conversation via `EventProvenance::fromConsumed()`
 *    (contract R-6 · ADR-MP-06), never `start()`.
 *
 * A structurally invalid payload cannot reach this handler through the only
 * production path: the relay hydrates producer-side BEFORE dispatch, and
 * `ChallengeRoutedHydrator` already rejects a missing `challengeId` or an
 * unsupported `schema_version` loudly (WP-3A). Should it ever arrive anyway,
 * `ChallengeRef` refuses an empty identity rather than opening a meaningless
 * adjudication — loud, never silent.
 *
 * Traceability: ADR-T21 · ADR-T16 · ADR-T3/T4 · ADR-MP-06 · PB-006 (*Registration ≠
 * Delivery*) · Cross_Context_Integration_Contract §5 · EPIC-004K §3 PM-1 ·
 * plan `.claude/plans/WP-4-apm-wiring.md` (G-2).
 */
final class ChallengeRoutedReactionHandler implements InboxHandler
{
    public function __construct(
        private readonly AdjudicationProcessManager $processes,
    ) {
    }

    public function consumerContext(): string
    {
        return 'Adjudication';
    }

    /**
     * @return list<string>
     */
    public function eventTypes(): array
    {
        return ['ChallengeRouted'];
    }

    public function handle(InboxMessage $message): void
    {
        $this->processes->openFor(
            ChallengeRef::fromString($this->stringField($message->payload, 'challengeId')),
        );
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
