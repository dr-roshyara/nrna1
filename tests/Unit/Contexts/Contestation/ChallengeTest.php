<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Contestation;

use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Challenge\Exception\IllegalChallengeTransition;
use App\Contexts\Contestation\Domain\Challenge\RaiserStandingRef;
use App\Contexts\Contestation\Domain\Challenge\SubmittedContent;
use App\Contexts\Contestation\Domain\Challenge\TargetRef;
use App\Contexts\Contestation\Domain\Events\ChallengeAdjudicated;
use App\Contexts\Contestation\Domain\Events\ChallengeAdmitted;
use App\Contexts\Contestation\Domain\Events\ChallengeRaised;
use App\Contexts\Contestation\Domain\Events\ChallengeResolved;
use App\Contexts\Contestation\Domain\Events\ChallengeRouted;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ChallengeTest extends TestCase
{
    private function raised(): Challenge
    {
        return Challenge::raise(
            ChallengeId::fromString('c-1'),
            RaiserStandingRef::fromString('standing-1'),
            TargetRef::fromString('election-outcome-1'),
            SubmittedContent::fromString('Tally dispute on post X.'),
            $this->at(),
        );
    }

    private function at(): DateTimeImmutable
    {
        return new DateTimeImmutable('2026-06-26T10:00:00+00:00');
    }

    // ── Happy path: Raised → Admitted → Routed → Resolved ────────────
    public function test_raise_creates_raised_and_records_event(): void
    {
        $c = $this->raised();

        $this->assertSame(ChallengeState::Raised, $c->state());
        $events = $c->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ChallengeRaised::class, $events[0]);
        $this->assertSame([], $c->pullEvents(), 'pullEvents must clear after release');
    }

    public function test_full_lifecycle_adjudicated_then_resolved(): void
    {
        // ADR-T20: Routed → Adjudicated (legal finality) → Resolved (operational).
        $c = $this->raised();
        $c->pullEvents();

        $c->admit($this->at());
        $c->route('jurisdiction-A', $this->at());

        $c->adjudicate(DeterminationId::fromString('d-1'), $this->at());
        $this->assertSame(ChallengeState::Adjudicated, $c->state());

        $c->resolve(DeterminationId::fromString('d-1'), $this->at());
        $this->assertSame(ChallengeState::Resolved, $c->state());

        $events = $c->pullEvents();
        $this->assertInstanceOf(ChallengeAdmitted::class, $events[0]);
        $this->assertInstanceOf(ChallengeRouted::class, $events[1]);
        $this->assertInstanceOf(ChallengeAdjudicated::class, $events[2]);
        $this->assertInstanceOf(ChallengeResolved::class, $events[3]);
    }

    public function test_adjudicate_routed_to_adjudicated_emits_event(): void
    {
        $c = $this->raised();
        $c->admit($this->at());
        $c->route('jurisdiction-A', $this->at());
        $c->pullEvents();

        $c->adjudicate(DeterminationId::fromString('d-7'), $this->at());

        $this->assertSame(ChallengeState::Adjudicated, $c->state());
        $events = $c->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ChallengeAdjudicated::class, $events[0]);
        $this->assertSame('d-7', $events[0]->determinationId->toString());
    }

    public function test_cannot_adjudicate_before_routed(): void
    {
        $c = $this->raised();
        $c->admit($this->at());
        $this->expectException(IllegalChallengeTransition::class);
        $c->adjudicate(DeterminationId::fromString('d-1'), $this->at());
    }

    public function test_cannot_resolve_before_adjudicated(): void
    {
        $c = $this->raised();
        $c->admit($this->at());
        $c->route('jurisdiction-A', $this->at());

        try {
            $c->resolve(DeterminationId::fromString('d-1'), $this->at());
            $this->fail('Expected IllegalChallengeTransition (must adjudicate first)');
        } catch (IllegalChallengeTransition) {
            $this->assertSame(ChallengeState::Routed, $c->state(), 'state unchanged');
        }
    }

    public function test_raised_can_be_dismissed(): void
    {
        $c = $this->raised();
        $c->dismiss('no standing', $this->at());
        $this->assertSame(ChallengeState::Dismissed, $c->state());
    }

    // ── Timeout: Lapsed emits NO event (Round 50-07 v1.2) ────────────
    public function test_lapse_transitions_to_lapsed_without_event(): void
    {
        $c = $this->raised();
        $c->pullEvents();

        $c->lapse($this->at());

        $this->assertSame(ChallengeState::Lapsed, $c->state());
        $this->assertSame([], $c->pullEvents(), 'Lapsed timeout must emit no domain event');
    }

    // ── Forbidden transitions throw, no mutation (illegal-transition policy)
    public function test_cannot_route_before_admit(): void
    {
        $c = $this->raised();

        try {
            $c->route('jurisdiction-A', $this->at());
            $this->fail('Expected IllegalChallengeTransition');
        } catch (IllegalChallengeTransition $e) {
            $this->assertSame(ChallengeState::Raised, $c->state(), 'state must be unchanged after forbidden transition');
        }
    }

    public function test_cannot_resolve_a_dismissed_challenge(): void
    {
        $c = $this->raised();
        $c->dismiss('no standing', $this->at());

        $this->expectException(IllegalChallengeTransition::class);
        $c->resolve(DeterminationId::fromString('d-1'), $this->at());
    }

    public function test_terminal_state_rejects_all_transitions(): void
    {
        $c = $this->raised();
        $c->admit($this->at());
        $c->route('jurisdiction-A', $this->at());
        $c->adjudicate(DeterminationId::fromString('d-1'), $this->at());
        $c->resolve(DeterminationId::fromString('d-1'), $this->at());

        $this->expectException(IllegalChallengeTransition::class);
        $c->admit($this->at());
    }

    // ── ChallengeContentValidation guard ─────────────────────────────
    public function test_empty_submitted_content_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        SubmittedContent::fromString('   ');
    }

    // ── Pure derived query (read-only; no mutation/auth/persistence/events)
    public function test_can_proceed_to_adjudication_only_when_routed(): void
    {
        $c = $this->raised();
        $this->assertFalse($c->canProceedToAdjudication(), 'Raised');

        $c->admit($this->at());
        $this->assertFalse($c->canProceedToAdjudication(), 'Admitted');

        $c->route('jurisdiction-A', $this->at());
        $this->assertTrue($c->canProceedToAdjudication(), 'Routed');

        $c->adjudicate(DeterminationId::fromString('d-1'), $this->at());
        $this->assertFalse($c->canProceedToAdjudication(), 'Adjudicated');

        // the query must not emit events: clear, query, then assert nothing new
        $c->pullEvents();
        $c->canProceedToAdjudication();
        $this->assertSame([], $c->pullEvents());
    }
}
