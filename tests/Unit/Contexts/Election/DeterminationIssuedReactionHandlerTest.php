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
use App\Contexts\Election\Domain\OrganisationId;
use App\Contexts\Election\Domain\Repository\ElectionRepository;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use PHPUnit\Framework\TestCase;

/**
 * PB-004 Step 3 (RED, round 3) — the Election reaction consumes `DeterminationIssued`
 * (payload schema version 2) via the frozen Messaging Platform Inbox port, resolves
 * the target Election within the determination's organisation, and drives the
 * aggregate. The handler *reacts*; the aggregate *decides*.
 *
 * Constitutional/business decisions encoded here (ARB round-2 rulings):
 *  - schema v1 (no election scope) is a **business incompatibility**, NOT corruption —
 *    a dedicated domain exception (infra later maps it to dead-letter/incident).
 *  - an **unknown Election is rejected**, never derived — Election reacts to existing
 *    elections; it does not provision them.
 *  - a determination is **never applied across organisation boundaries** — resolution
 *    is organisation-scoped (tenant lives at the repository/infra boundary, keeping the
 *    Election *domain* tenant-free per ADR-T16 / Adjudication precedent). [ARB: confirm
 *    org placement — repository boundary vs domain aggregate.]
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
        $handler = new DeterminationIssuedReactionHandler($this->repository(null), $this->outbox());

        $this->assertInstanceOf(InboxHandler::class, $handler);
        $this->assertSame('Election', $handler->consumerContext());
        $this->assertContains('DeterminationIssued', $handler->eventTypes());
    }

    public function test_reconstructs_election_from_schema_v2_payload_and_applies_correction(): void
    {
        $outbox = $this->outbox();
        // The Election already EXISTS (org-1) — the handler resolves and applies to it.
        $repo = $this->repository(ElectionId::fromString('election-77'), 'org-1');

        (new DeterminationIssuedReactionHandler($repo, $outbox))->handle($this->schemaV2Message('org-1'));

        $this->assertCount(1, $outbox->events);
        $this->assertInstanceOf(ElectionCorrectionApplied::class, $outbox->events[0]);
        // Proves the local model was reconstructed from contestedOutcome.electionId:
        $this->assertSame('election-77', $outbox->events[0]->electionId->toString());
        $this->assertSame('det-9', $outbox->events[0]->determinationId->toString());
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
        (new DeterminationIssuedReactionHandler($this->repository(null), $this->outbox()))->handle($v1);
    }

    // (B) unknown Election → rejected, NEVER derived. Election reacts to existing
    // elections; it does not provision them.
    public function test_unknown_election_is_rejected_not_derived(): void
    {
        $repo = $this->repository(null); // resolves nothing
        $outbox = $this->outbox();

        try {
            (new DeterminationIssuedReactionHandler($repo, $outbox))->handle($this->schemaV2Message('org-1'));
            $this->fail('Expected CannotApplyDeterminationToUnknownElection');
        } catch (CannotApplyDeterminationToUnknownElection) {
            $this->assertSame([], $outbox->events, 'no correction may be emitted for an unknown election');
        }
    }

    // (C) constitutional invariant: a determination is NEVER applied across
    // organisation boundaries. Resolution is org-scoped — a determination from org-1
    // cannot reach an election that belongs to org-2 (it resolves to "unknown").
    public function test_determination_never_applies_across_organisation_boundaries(): void
    {
        // Election exists only in org-2; the determination arrives under org-1.
        $repo = $this->repository(ElectionId::fromString('election-77'), 'org-2');
        $outbox = $this->outbox();

        $this->expectException(CannotApplyDeterminationToUnknownElection::class);
        (new DeterminationIssuedReactionHandler($repo, $outbox))->handle($this->schemaV2Message('org-1'));
    }

    // ── intended in-memory collaborators (define the intended Election ports) ──

    /** Organisation-scoped resolution (tenant at the repository boundary; domain stays tenant-free). */
    private function repository(?ElectionId $existing, string $inOrganisation = 'org-1'): ElectionRepository
    {
        return new class($existing, $inOrganisation) implements ElectionRepository {
            public function __construct(private ?ElectionId $existing, private string $inOrganisation)
            {
            }

            public function find(ElectionId $id, OrganisationId $organisation): ?Election
            {
                if ($this->existing !== null
                    && $id->toString() === $this->existing->toString()
                    && $organisation->toString() === $this->inOrganisation) {
                    return Election::identifiedBy($this->existing);
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

            public function enqueue(object ...$events): void
            {
                $this->events = array_merge($this->events, $events);
            }
        };
    }
}
