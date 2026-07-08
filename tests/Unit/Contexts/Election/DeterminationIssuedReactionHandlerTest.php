<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election;

use App\Contexts\Election\Application\DeterminationIssuedReactionHandler;
use App\Contexts\Election\Application\Port\ReactionEventOutbox;
use App\Contexts\Election\Domain\Election;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\Events\ElectionCorrectionApplied;
use App\Contexts\Election\Domain\Repository\ElectionRepository;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Application\Inbox\PermanentInboxFailure;
use PHPUnit\Framework\TestCase;

/**
 * PB-004 Step 3 (RED) — the Election reaction consumes `DeterminationIssued`
 * (payload schema version 2) via the frozen Messaging Platform Inbox port, and
 * reconstructs its OWN local model (ADR-T16) from the payload — reading the
 * electionId from `contestedOutcome`. Then it drives the Election aggregate.
 *
 * ARR: Election is a CONSUMER of the frozen Messaging Platform (implements the
 * Shared `InboxHandler` port) — no change to Shared.
 */
final class DeterminationIssuedReactionHandlerTest extends TestCase
{
    private function schemaV2Message(): InboxMessage
    {
        return new InboxMessage(
            eventId: 'evt-1',
            eventType: 'DeterminationIssued',
            payload: [
                'schema_version' => 2,
                'determinationId' => 'det-9',
                'challengeRef' => 'ch-9',
                'outcome' => 'upheld',
                'legitimacy' => 'legitimate',
                // schema v2 additive block — carries the electionId (ADR-PL-01):
                'contestedOutcome' => [
                    'electionId' => 'election-77',
                    'type' => 'election_result',
                    'targetId' => 'result-1',
                ],
                'occurredAt' => '2026-07-08T10:00:00+00:00',
            ],
            organisationId: 'org-1',
        );
    }

    public function test_handler_is_an_election_inbox_consumer_for_determination_issued(): void
    {
        $handler = $this->handler($this->outbox());

        $this->assertInstanceOf(InboxHandler::class, $handler);
        $this->assertSame('Election', $handler->consumerContext());
        $this->assertContains('DeterminationIssued', $handler->eventTypes());
    }

    public function test_reconstructs_election_from_schema_v2_payload_and_applies_correction(): void
    {
        $outbox = $this->outbox();

        $this->handler($outbox)->handle($this->schemaV2Message());

        $this->assertCount(1, $outbox->events);
        $this->assertInstanceOf(ElectionCorrectionApplied::class, $outbox->events[0]);
        // Proves the local model was reconstructed from contestedOutcome.electionId:
        $this->assertSame('election-77', $outbox->events[0]->electionId->toString());
        $this->assertSame('det-9', $outbox->events[0]->determinationId->toString());
    }

    // Backward compatibility (explicit): a schema_version 1 DeterminationIssued has
    // NO contestedOutcome, so Election cannot resolve its target election. Retrying
    // cannot add the field → it is a PERMANENT failure (dead-letter loudly), never a
    // silent drop and never an endless park. (ADR-T5 vPrevious + Blueprint §7 F2.)
    public function test_schema_v1_determination_without_contested_outcome_is_permanently_failed(): void
    {
        $v1 = new InboxMessage(
            eventId: 'evt-2',
            eventType: 'DeterminationIssued',
            payload: [
                // no schema_version (=> 1), no contestedOutcome
                'determinationId' => 'det-v1',
                'challengeRef' => 'ch-v1',
                'outcome' => 'upheld',
                'legitimacy' => 'legitimate',
                'occurredAt' => '2026-07-08T10:00:00+00:00',
            ],
            organisationId: 'org-1',
        );

        $this->expectException(PermanentInboxFailure::class);
        $this->handler($this->outbox())->handle($v1);
    }

    // Missing Election state (constitutional decision — flagged for ARB confirmation):
    // a DeterminationIssued is authority-issued and names its election via
    // contestedOutcome.electionId. Election TRUSTS that identity and DERIVES the
    // correction-holder for it (no prior state required) — it does NOT reject, park,
    // or invent a *different* election. The correction is recorded for the named election.
    public function test_unknown_election_is_derived_from_the_determination_not_rejected(): void
    {
        $outbox = $this->outbox();

        // Repository with NO stored elections — get() derives a fresh aggregate.
        $repo = new class implements ElectionRepository {
            public function get(ElectionId $id): Election
            {
                return Election::identifiedBy($id); // derive; do not reject
            }

            public function save(Election $election): void
            {
            }
        };

        (new DeterminationIssuedReactionHandler($repo, $outbox))->handle($this->schemaV2Message());

        $this->assertCount(1, $outbox->events, 'unknown election is derived and corrected, not rejected');
        $this->assertSame('election-77', $outbox->events[0]->electionId->toString());
    }

    // ── intended in-memory collaborators (define the intended Election ports) ──
    private function handler(ReactionEventOutbox $outbox): DeterminationIssuedReactionHandler
    {
        $repo = new class implements ElectionRepository {
            public function get(ElectionId $id): Election
            {
                return Election::identifiedBy($id);
            }

            public function save(Election $election): void
            {
            }
        };

        return new DeterminationIssuedReactionHandler($repo, $outbox);
    }

    private function outbox(): ReactionEventOutbox
    {
        return new class implements ReactionEventOutbox {
            /** @var list<object> */
            public array $events = [];

            public function enqueue(object ...$events): void
            {
                $this->events = array_merge($this->events, $events);
            }
        };
    }
}
