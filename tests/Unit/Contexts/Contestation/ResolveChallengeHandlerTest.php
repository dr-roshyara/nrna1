<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Contestation;

use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Application\ResolveChallengeHandler;
use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Events\ChallengeResolved;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use App\Contexts\Shared\Application\Inbox\CausalPreconditionMissing;
use App\Contexts\Shared\Application\Inbox\IdempotentReplay;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Infrastructure\Shared\Clock\FrozenClock;
use PHPUnit\Framework\TestCase;

/**
 * PB-005 Step 5A (RED) — the Contestation reaction to `ElectionCorrectionApplied`:
 * `resolve()` (Adjudicated→Resolved, `ChallengeResolved`). The correlation key is the
 * **`determinationId`** (the integration event carries no `challengeId`), so the handler
 * finds the Challenge via `findByDeterminationId`. If the Challenge is not yet adjudicated
 * (no such determination), the correction is **temporally premature** → the handler throws
 * `CausalPreconditionMissing` so the Inbox parks + re-drives it (never a business error).
 *
 * The domain event `ChallengeResolved` stays minimal (ARB F-2 ruling); the `resolution`
 * enrichment of the *published* Integration Event is a 5C concern, not tested here.
 */
final class ResolveChallengeHandlerTest extends TestCase
{
    private const APPLIED_AT = '2026-07-08T10:06:00+00:00';

    private function message(string $determinationId = 'det-1'): InboxMessage
    {
        return new InboxMessage(
            eventId: 'evt-corr-'.$determinationId,
            eventType: 'ElectionCorrectionApplied',
            payload: [
                'schema_version' => 1,
                'electionId' => 'election-77',
                'determinationId' => $determinationId,
                'correctionType' => 'contained_only',
                'appliedAt' => '2026-07-08T10:05:00+00:00',
            ],
            organisationId: 'org-1',
        );
    }

    public function test_is_a_contestation_inbox_consumer_for_election_correction_applied(): void
    {
        $handler = new ResolveChallengeHandler($this->repo(), $this->outbox(), FrozenClock::at(self::APPLIED_AT));

        $this->assertInstanceOf(InboxHandler::class, $handler);
        $this->assertSame('Contestation', $handler->consumerContext());
        $this->assertContains('ElectionCorrectionApplied', $handler->eventTypes());
    }

    public function test_resolves_the_adjudicated_challenge_found_by_determination_id(): void
    {
        $repo = $this->repo($this->adjudicated('ch-1', 'det-1'));
        $outbox = $this->outbox();

        (new ResolveChallengeHandler($repo, $outbox, FrozenClock::at(self::APPLIED_AT)))->handle($this->message('det-1'));

        $this->assertCount(1, $outbox->events);
        $this->assertInstanceOf(ChallengeResolved::class, $outbox->events[0]);
        $this->assertSame(ChallengeState::Resolved, $repo->saved('ch-1')->state());
    }

    public function test_correction_before_adjudication_is_temporally_premature_and_parks(): void
    {
        // No Challenge carries this determination yet (adjudication not landed) → park.
        $repo = $this->repo(); // empty
        $outbox = $this->outbox();

        try {
            (new ResolveChallengeHandler($repo, $outbox, FrozenClock::at(self::APPLIED_AT)))->handle($this->message('det-1'));
            $this->fail('Expected CausalPreconditionMissing (park + re-drive)');
        } catch (CausalPreconditionMissing) {
            $this->assertSame([], $outbox->events, 'no event may be emitted for a premature correction');
        }
    }

    public function test_re_delivery_of_an_already_resolved_challenge_is_an_idempotent_replay(): void
    {
        $repo = $this->repo($this->resolved('ch-1', 'det-1'));

        $this->expectException(IdempotentReplay::class);
        (new ResolveChallengeHandler($repo, $this->outbox(), FrozenClock::at(self::APPLIED_AT)))->handle($this->message('det-1'));
    }

    // ── in-memory collaborators + fixtures ──

    private function adjudicated(string $id, string $determinationId): Challenge
    {
        return Challenge::reconstitute(ChallengeId::fromString($id), ChallengeState::Adjudicated, DeterminationId::fromString($determinationId));
    }

    private function resolved(string $id, string $determinationId): Challenge
    {
        return Challenge::reconstitute(ChallengeId::fromString($id), ChallengeState::Resolved, DeterminationId::fromString($determinationId));
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
