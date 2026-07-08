<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Contestation;

use App\Contexts\Contestation\Application\AdjudicateChallengeHandler;
use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Events\ChallengeAdjudicated;
use App\Contexts\Contestation\Domain\Events\ChallengeResolved;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use App\Contexts\Shared\Application\Inbox\IdempotentReplay;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Application\Inbox\PermanentInboxFailure;
use App\Infrastructure\Shared\Clock\FrozenClock;
use PHPUnit\Framework\TestCase;

/**
 * PB-005 Step 5A (RED) — the Contestation reaction to `DeterminationIssued`:
 * `adjudicate()` (Routed→Adjudicated, `ChallengeAdjudicated`), with the **Dismissed
 * short-circuit** (also `resolve()` in the same reaction, T5'). Behaviour-first (ER-07);
 * in-memory ports (no DB). Timestamps come from the injected clock (application time).
 *
 * Replay classification (F-1) is expressed as business conditions in the domain, which
 * the handler translates to Inbox markers: semantic-identical → IdempotentReplay;
 * conflicting determination → PermanentInboxFailure (never silently applied).
 */
final class AdjudicateChallengeHandlerTest extends TestCase
{
    private const APPLIED_AT = '2026-07-08T10:04:00+00:00';

    private function message(string $outcome = 'upheld', string $challengeRef = 'ch-1', string $determinationId = 'det-1'): InboxMessage
    {
        return new InboxMessage(
            eventId: 'evt-'.$determinationId,
            eventType: 'DeterminationIssued',
            payload: [
                'schema_version' => 2,
                'determinationId' => $determinationId,
                'challengeRef' => $challengeRef,
                'outcome' => $outcome,
                'legitimacy' => 'legitimate',
                'occurredAt' => '2026-07-08T10:00:00+00:00',
            ],
            organisationId: 'org-1',
        );
    }

    public function test_is_a_contestation_inbox_consumer_for_determination_issued(): void
    {
        $handler = new AdjudicateChallengeHandler($this->repo(), $this->outbox(), FrozenClock::at(self::APPLIED_AT));

        $this->assertInstanceOf(InboxHandler::class, $handler);
        $this->assertSame('Contestation', $handler->consumerContext());
        $this->assertContains('DeterminationIssued', $handler->eventTypes());
    }

    public function test_upheld_determination_adjudicates_a_routed_challenge(): void
    {
        $repo = $this->repo($this->routed('ch-1'));
        $outbox = $this->outbox();

        (new AdjudicateChallengeHandler($repo, $outbox, FrozenClock::at(self::APPLIED_AT)))->handle($this->message('upheld', 'ch-1', 'det-1'));

        $this->assertCount(1, $outbox->events);
        $this->assertInstanceOf(ChallengeAdjudicated::class, $outbox->events[0]);
        $this->assertSame(ChallengeState::Adjudicated, $repo->saved('ch-1')->state());
    }

    public function test_dismissed_determination_adjudicates_and_resolves_in_one_reaction(): void
    {
        // Dismissed short-circuit (T5'): Election stays silent, so Contestation self-resolves.
        $repo = $this->repo($this->routed('ch-1'));
        $outbox = $this->outbox();

        (new AdjudicateChallengeHandler($repo, $outbox, FrozenClock::at(self::APPLIED_AT)))->handle($this->message('dismissed', 'ch-1', 'det-1'));

        $this->assertInstanceOf(ChallengeAdjudicated::class, $outbox->events[0]);
        $this->assertInstanceOf(ChallengeResolved::class, $outbox->events[1]);
        $this->assertSame(ChallengeState::Resolved, $repo->saved('ch-1')->state());
    }

    public function test_re_delivery_of_the_same_determination_is_an_idempotent_replay(): void
    {
        // Already adjudicated with the SAME determination → same constitutional fact.
        $repo = $this->repo($this->adjudicated('ch-1', 'det-1'));

        $this->expectException(IdempotentReplay::class);
        (new AdjudicateChallengeHandler($repo, $this->outbox(), FrozenClock::at(self::APPLIED_AT)))->handle($this->message('upheld', 'ch-1', 'det-1'));
    }

    public function test_a_conflicting_determination_is_a_permanent_business_failure(): void
    {
        // Already adjudicated with a DIFFERENT determination → one binding determination
        // per challenge; never silently applied.
        $repo = $this->repo($this->adjudicated('ch-1', 'det-1'));

        $this->expectException(PermanentInboxFailure::class);
        (new AdjudicateChallengeHandler($repo, $this->outbox(), FrozenClock::at(self::APPLIED_AT)))->handle($this->message('upheld', 'ch-1', 'det-2'));
    }

    // ── in-memory collaborators + fixtures ──

    private function routed(string $id): Challenge
    {
        return Challenge::reconstitute(ChallengeId::fromString($id), ChallengeState::Routed, null);
    }

    private function adjudicated(string $id, string $determinationId): Challenge
    {
        return Challenge::reconstitute(ChallengeId::fromString($id), ChallengeState::Adjudicated, DeterminationId::fromString($determinationId));
    }

    private function repo(Challenge ...$seed): ChallengeRepository
    {
        return new class(...$seed) implements ChallengeRepository {
            /** @var array<string, Challenge> */
            private array $byId = [];

            public function __construct(Challenge ...$seed)
            {
                foreach ($seed as $c) {
                    $this->byId[$c->id()->toString()] = $c;
                }
            }

            public function nextIdentity(): ChallengeId
            {
                return ChallengeId::fromString('ch-generated');
            }

            public function save(Challenge $challenge): void
            {
                $this->byId[$challenge->id()->toString()] = $challenge;
            }

            public function get(ChallengeId $id): Challenge
            {
                return $this->byId[$id->toString()];
            }

            public function find(ChallengeId $id): ?Challenge
            {
                return $this->byId[$id->toString()] ?? null;
            }

            public function findByDeterminationId(DeterminationId $id): ?Challenge
            {
                foreach ($this->byId as $c) {
                    $d = $c->adjudicatedDeterminationId();
                    if ($d !== null && $d->toString() === $id->toString()) {
                        return $c;
                    }
                }

                return null;
            }

            public function saved(string $id): Challenge
            {
                return $this->byId[$id];
            }
        };
    }

    private function outbox(): ChallengeEventOutbox
    {
        return new class implements ChallengeEventOutbox {
            /** @var list<object> */
            public array $events = [];

            public function enqueue(object ...$events): void
            {
                $this->events = array_merge($this->events, $events);
            }
        };
    }
}
