<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election;

use App\Contexts\Election\Application\DeterminationIssuedReactionHandler;
use App\Contexts\Election\Application\Port\ReactionEventOutbox;
use App\Contexts\Election\Domain\Election;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\Events\ElectionCorrectionApplied;
use App\Contexts\Election\Domain\Exception\CannotApplyDeterminationToUnknownElection;
use App\Contexts\Election\Domain\Exception\DeterminationLacksElectionScope;
use App\Contexts\Election\Domain\Repository\ElectionRepository;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Infrastructure\Shared\Clock\FrozenClock;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use PHPUnit\Framework\TestCase;

/**
 * PB-004 Step 3 — the Election reaction consumes `DeterminationIssued` (payload schema
 * version 2) via the frozen Messaging Platform Inbox port, resolves the target Election
 * within the determination's organisation, and drives the aggregate. The handler
 * *reacts*; the aggregate *decides*.
 *
 * Constitutional/business decisions encoded here (ARB round-2/3 rulings):
 *  - schema v1 (no election scope) is a **business incompatibility**, NOT corruption —
 *    a dedicated domain exception (infra later maps it to dead-letter/incident).
 *  - an **unknown Election is rejected**, never derived — Election reacts to existing
 *    elections; it does not provision them.
 *  - a determination is **never applied across organisation boundaries** — resolution
 *    is organisation-scoped.
 *
 * Repository boundary (ARB round-3 refinement): the repository answers a pure DOMAIN
 * question — `find(ElectionId): ?Election`. Organisation scope is NOT part of the
 * contract; it is applied by the organisation-scoped repository implementation (as the
 * concrete Eloquent repository is, under the ambient tenant), keeping the Election
 * *domain* tenant-free (ADR-T16). A cross-organisation determination therefore simply
 * resolves to "unknown".
 */
final class DeterminationIssuedReactionHandlerTest extends TestCase
{
    private function schemaV2Message(string $organisationId = 'org-1'): InboxMessage
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
                'contestedOutcome' => ['electionId' => 'election-77', 'type' => 'election_result', 'targetId' => 'result-1'],
                'occurredAt' => '2026-07-08T10:00:00+00:00',
            ],
            organisationId: $organisationId,
        );
    }

    public function test_handler_is_an_election_inbox_consumer_for_determination_issued(): void
    {
        $handler = $this->handler($this->repositoryScopedTo('org-1'), $this->outbox());

        $this->assertInstanceOf(InboxHandler::class, $handler);
        $this->assertSame('Election', $handler->consumerContext());
        $this->assertContains('DeterminationIssued', $handler->eventTypes());
    }

    public function test_reconstructs_election_from_schema_v2_payload_and_applies_correction(): void
    {
        $outbox = $this->outbox();
        // The Election already EXISTS in the ambient org (org-1) — the handler resolves and applies to it.
        $repo = $this->repositoryScopedTo('org-1', ElectionId::fromString('election-77'), 'org-1');
        $message = $this->schemaV2Message('org-1');

        // Application timestamp (when Election applies the correction) is 10:04 — deliberately
        // LATER than the determination's issuance time (10:00) carried in the payload.
        $this->handler($repo, $outbox)->handle($message);

        $this->assertCount(1, $outbox->events);
        $this->assertInstanceOf(ElectionCorrectionApplied::class, $outbox->events[0]);
        // Proves the local model was reconstructed from contestedOutcome.electionId:
        $this->assertSame('election-77', $outbox->events[0]->electionId->toString());
        $this->assertSame('det-9', $outbox->events[0]->determinationId->toString());

        // Both timestamps coexist, unmutated, and are DIFFERENT facts:
        //  - DeterminationIssued.occurredAt (issuance) stays 10:00 on the inbound message;
        //  - ElectionCorrectionApplied.appliedAt (application) is the clock's 10:04.
        $this->assertSame('2026-07-08T10:00:00+00:00', $message->payload['occurredAt']);
        $this->assertSame('2026-07-08T10:04:00+00:00', $outbox->events[0]->appliedAt->format(DATE_ATOM));
    }

    // (A) schema v1 is historically valid but lacks the election scope this consumer
    // needs — a BUSINESS INCOMPATIBILITY (not corruption, not transient). A dedicated
    // domain exception; infrastructure later maps it to dead-letter/incident.
    public function test_schema_v1_without_election_scope_is_a_business_incompatibility(): void
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

        $this->expectException(DeterminationLacksElectionScope::class);
        $this->handler($this->repositoryScopedTo('org-1'), $this->outbox())->handle($v1);
    }

    // (B) unknown Election → rejected, NEVER derived. Election reacts to existing
    // elections; it does not provision them.
    public function test_unknown_election_is_rejected_not_derived(): void
    {
        $repo = $this->repositoryScopedTo('org-1'); // scope holds no such election
        $outbox = $this->outbox();

        try {
            $this->handler($repo, $outbox)->handle($this->schemaV2Message('org-1'));
            $this->fail('Expected CannotApplyDeterminationToUnknownElection');
        } catch (CannotApplyDeterminationToUnknownElection) {
            $this->assertSame([], $outbox->events, 'no correction may be emitted for an unknown election');
        }
    }

    // (C) constitutional invariant: a determination is NEVER applied across
    // organisation boundaries. The election exists, but in org-2; a determination
    // arriving under org-1 resolves against org-1's scope and finds nothing.
    public function test_determination_never_applies_across_organisation_boundaries(): void
    {
        // Determination arrives under org-1; election-77 belongs to org-2.
        $repo = $this->repositoryScopedTo('org-1', ElectionId::fromString('election-77'), 'org-2');
        $outbox = $this->outbox();

        $this->expectException(CannotApplyDeterminationToUnknownElection::class);
        $this->handler($repo, $outbox)->handle($this->schemaV2Message('org-1'));
    }

    // ── intended in-memory collaborators (define the intended Election ports) ──

    /**
     * Build the handler with a FROZEN application clock. The default 10:04 is deliberately
     * later than the payload's determination-issuance time (10:00), so tests can prove the
     * event records the application timestamp, not the determination's.
     */
    private function handler(
        ElectionRepository $repository,
        ReactionEventOutbox $outbox,
        string $applicationTimestamp = '2026-07-08T10:04:00+00:00',
    ): DeterminationIssuedReactionHandler {
        return new DeterminationIssuedReactionHandler($repository, $outbox, FrozenClock::at($applicationTimestamp));
    }

    /**
     * A repository already scoped to the ambient organisation (as the concrete Eloquent
     * repository is, under TenantContext). `find()` asks a pure domain question; the
     * organisation scope is applied HERE, never as part of the repository contract.
     */
    private function repositoryScopedTo(
        string $ambientOrganisation,
        ?ElectionId $election = null,
        string $electionOrganisation = 'org-1',
    ): ElectionRepository {
        return new class($ambientOrganisation, $election, $electionOrganisation) implements ElectionRepository {
            public function __construct(
                private string $ambientOrganisation,
                private ?ElectionId $election,
                private string $electionOrganisation,
            ) {
            }

            public function find(ElectionId $id): ?Election
            {
                // The scoped repository can only see elections within its own organisation.
                if ($this->election !== null
                    && $id->toString() === $this->election->toString()
                    && $this->electionOrganisation === $this->ambientOrganisation) {
                    return Election::identifiedBy($this->election);
                }

                return null;
            }

            public function save(Election $election): void
            {
            }
        };
    }

    private function outbox(): ReactionEventOutbox
    {
        return new class implements ReactionEventOutbox {
            /** @var list<object> */
            public array $events = [];

            public function enqueue(EventProvenance $provenance, object ...$events): void
            {
                $this->events = array_merge($this->events, $events);
            }
        };
    }
}
