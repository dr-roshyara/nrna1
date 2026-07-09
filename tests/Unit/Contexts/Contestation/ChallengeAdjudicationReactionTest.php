<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Contestation;

use App\Contexts\Contestation\Application\ChallengeAdjudicationReaction;
use App\Contexts\Contestation\Application\ChallengeResolvedIntegration;
use App\Contexts\Contestation\Application\Exception\DeterminationAlreadyApplied;
use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Application\Resolution;
use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Challenge\DeterminationOutcome;
use App\Contexts\Contestation\Domain\Challenge\Exception\ConflictingDetermination;
use App\Contexts\Contestation\Domain\Events\ChallengeAdjudicated;
use App\Contexts\Contestation\Domain\Events\ChallengeResolved;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use DateTimeImmutable;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use PHPUnit\Framework\TestCase;
use Tests\Support\Contestation\InMemoryChallengeRepository;

/**
 * PB-005 Step 5A (RED) — the adjudication reaction expressed as BUSINESS behaviour only.
 * It asserts business outcomes and business conditions (`ConflictingDetermination`,
 * `DeterminationAlreadyApplied`) — NOT inbox markers. The translation of these business
 * conditions into messaging outcomes is verified separately in
 * {@see ChallengeReactionInboxTranslationTest}, keeping the Application layer decoupled
 * from the messaging implementation (ARB F-1 boundary).
 */
final class ChallengeAdjudicationReactionTest extends TestCase
{
    private function at(): DateTimeImmutable
    {
        return new DateTimeImmutable('2026-07-08T10:04:00+00:00');
    }

    public function test_upheld_determination_adjudicates_a_routed_challenge(): void
    {
        $repo = $this->repo($this->routed('ch-1'));
        $outbox = $this->outbox();

        $this->reaction($repo, $outbox)->on(ChallengeId::fromString('ch-1'), DeterminationId::fromString('det-1'), DeterminationOutcome::Upheld, $this->at(), EventProvenance::fromConsumed('corr-test', 'evt-cause'));

        $this->assertCount(1, $outbox->events);
        $this->assertInstanceOf(ChallengeAdjudicated::class, $outbox->events[0]);
        $this->assertSame(ChallengeState::Adjudicated, $repo->saved('ch-1')->state());
    }

    public function test_dismissed_determination_adjudicates_and_resolves_in_one_reaction(): void
    {
        $repo = $this->repo($this->routed('ch-1'));
        $outbox = $this->outbox();

        $this->reaction($repo, $outbox)->on(ChallengeId::fromString('ch-1'), DeterminationId::fromString('det-1'), DeterminationOutcome::Dismissed, $this->at(), EventProvenance::fromConsumed('corr-test', 'evt-cause'));

        // Adjudicated raw; the resolved event is published as the integration carrier with
        // resolution=Dismissed (5C seam) — the domain event stays minimal.
        $this->assertInstanceOf(ChallengeAdjudicated::class, $outbox->events[0]);
        $this->assertInstanceOf(ChallengeResolvedIntegration::class, $outbox->events[1]);
        $this->assertInstanceOf(ChallengeResolved::class, $outbox->events[1]->event);
        $this->assertSame(Resolution::Dismissed, $outbox->events[1]->resolution);
        $this->assertSame(ChallengeState::Resolved, $repo->saved('ch-1')->state());
    }

    public function test_re_delivery_of_the_same_determination_is_a_business_replay(): void
    {
        $repo = $this->repo($this->adjudicated('ch-1', 'det-1'));

        $this->expectException(DeterminationAlreadyApplied::class); // business condition, not an inbox marker
        $this->reaction($repo, $this->outbox())->on(ChallengeId::fromString('ch-1'), DeterminationId::fromString('det-1'), DeterminationOutcome::Upheld, $this->at(), EventProvenance::fromConsumed('corr-test', 'evt-cause'));
    }

    public function test_a_different_determination_on_an_adjudicated_challenge_conflicts(): void
    {
        $repo = $this->repo($this->adjudicated('ch-1', 'det-1'));

        $this->expectException(ConflictingDetermination::class); // domain invariant: one binding determination per challenge
        $this->reaction($repo, $this->outbox())->on(ChallengeId::fromString('ch-1'), DeterminationId::fromString('det-2'), DeterminationOutcome::Upheld, $this->at(), EventProvenance::fromConsumed('corr-test', 'evt-cause'));
    }

    // ── collaborators + fixtures ──

    private function reaction(ChallengeRepository $repo, ChallengeEventOutbox $outbox): ChallengeAdjudicationReaction
    {
        return new ChallengeAdjudicationReaction($repo, $outbox);
    }

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
        return new InMemoryChallengeRepository(...$seed);
    }

    private function outbox(): ChallengeEventOutbox
    {
        return new class implements ChallengeEventOutbox {
            /** @var list<object> */
            public array $events = [];

            public function enqueue(EventProvenance $provenance, object ...$events): void
            {
                $this->events = array_merge($this->events, $events);
            }
        };
    }
}
