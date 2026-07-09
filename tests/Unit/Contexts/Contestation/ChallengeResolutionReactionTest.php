<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Contestation;

use App\Contexts\Contestation\Application\ChallengeResolutionReaction;
use App\Contexts\Contestation\Application\ChallengeResolvedIntegration;
use App\Contexts\Contestation\Application\Exception\AwaitingAdjudication;
use App\Contexts\Contestation\Application\Exception\DeterminationAlreadyApplied;
use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Application\Resolution;
use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Events\ChallengeResolved;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use DateTimeImmutable;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use PHPUnit\Framework\TestCase;
use Tests\Support\Contestation\InMemoryChallengeRepository;

/**
 * PB-005 Step 5A (RED) — the resolution reaction as BUSINESS behaviour only. The
 * correlation key is `determinationId` (the integration event carries no `challengeId`).
 * A correction that arrives before its adjudication is a BUSINESS condition —
 * `AwaitingAdjudication` (temporally premature) — NOT an inbox marker; the translation to
 * a park is verified in {@see ChallengeReactionInboxTranslationTest}.
 */
final class ChallengeResolutionReactionTest extends TestCase
{
    private function at(): DateTimeImmutable
    {
        return new DateTimeImmutable('2026-07-08T10:06:00+00:00');
    }

    public function test_resolves_the_adjudicated_challenge_found_by_determination_id(): void
    {
        $repo = $this->repo($this->adjudicated('ch-1', 'det-1'));
        $outbox = $this->outbox();

        $this->reaction($repo, $outbox)->on(DeterminationId::fromString('det-1'), $this->at(), EventProvenance::fromConsumed('corr-test', 'evt-cause'));

        // 5C emergent seam: the published item is the integration carrier (minimal domain
        // event + Application-supplied resolution), not the raw domain event.
        $this->assertCount(1, $outbox->events);
        $this->assertInstanceOf(ChallengeResolvedIntegration::class, $outbox->events[0]);
        $this->assertInstanceOf(ChallengeResolved::class, $outbox->events[0]->event);
        $this->assertSame(Resolution::Upheld, $outbox->events[0]->resolution);
        $this->assertSame(ChallengeState::Resolved, $repo->saved('ch-1')->state());
    }

    public function test_correction_before_adjudication_is_awaiting_adjudication(): void
    {
        // No Challenge carries this determination yet → temporally premature (business).
        $this->expectException(AwaitingAdjudication::class);
        $this->reaction($this->repo(), $this->outbox())->on(DeterminationId::fromString('det-1'), $this->at(), EventProvenance::fromConsumed('corr-test', 'evt-cause'));
    }

    public function test_re_delivery_of_an_already_resolved_challenge_is_a_business_replay(): void
    {
        $repo = $this->repo($this->resolved('ch-1', 'det-1'));

        $this->expectException(DeterminationAlreadyApplied::class);
        $this->reaction($repo, $this->outbox())->on(DeterminationId::fromString('det-1'), $this->at(), EventProvenance::fromConsumed('corr-test', 'evt-cause'));
    }

    // ── collaborators + fixtures ──

    private function reaction(ChallengeRepository $repo, ChallengeEventOutbox $outbox): ChallengeResolutionReaction
    {
        return new ChallengeResolutionReaction($repo, $outbox);
    }

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
