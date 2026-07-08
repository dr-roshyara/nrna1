<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Contestation;

use App\Contexts\Contestation\Application\AdjudicateChallengeHandler;
use App\Contexts\Contestation\Application\ChallengeAdjudicationReaction;
use App\Contexts\Contestation\Application\ChallengeResolutionReaction;
use App\Contexts\Contestation\Application\Exception\AwaitingAdjudication;
use App\Contexts\Contestation\Application\Exception\DeterminationAlreadyApplied;
use App\Contexts\Contestation\Application\Inbox\ChallengeReactionInboxTranslator;
use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Application\ResolveChallengeHandler;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Challenge\Exception\ConflictingDetermination;
use App\Contexts\Contestation\Domain\Challenge\Exception\IllegalChallengeTransition;
use App\Contexts\Shared\Application\Inbox\CausalPreconditionMissing;
use App\Contexts\Shared\Application\Inbox\IdempotentReplay;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\PermanentInboxFailure;
use App\Infrastructure\Shared\Clock\FrozenClock;
use PHPUnit\Framework\TestCase;
use Tests\Support\Contestation\InMemoryChallengeRepository;

/**
 * PB-005 Step 5A (RED) — the translation boundary (ARB F-1). Business conditions raised by
 * the reactions are mapped, HERE and only here, to the Messaging Platform's operational
 * markers. This is the single place coupled to messaging semantics; the reaction
 * behaviour tests stay messaging-agnostic.
 *
 *   AwaitingAdjudication      → CausalPreconditionMissing (park + re-drive)
 *   DeterminationAlreadyApplied → IdempotentReplay        (Processed, no-op)
 *   ConflictingDetermination  → PermanentInboxFailure     (dead-letter + incident)
 *   IllegalChallengeTransition → PermanentInboxFailure
 */
final class ChallengeReactionInboxTranslationTest extends TestCase
{
    private function translator(): ChallengeReactionInboxTranslator
    {
        return new ChallengeReactionInboxTranslator();
    }

    public function test_premature_correction_translates_to_a_park(): void
    {
        $marker = $this->translator()->toInboxOutcome(new AwaitingAdjudication('await'));
        $this->assertInstanceOf(CausalPreconditionMissing::class, $marker);
    }

    public function test_semantic_replay_translates_to_idempotent_replay(): void
    {
        $marker = $this->translator()->toInboxOutcome(new DeterminationAlreadyApplied('replay'));
        $this->assertInstanceOf(IdempotentReplay::class, $marker);
    }

    public function test_conflicting_determination_translates_to_a_permanent_failure(): void
    {
        $marker = $this->translator()->toInboxOutcome(new ConflictingDetermination('conflict'));
        $this->assertInstanceOf(PermanentInboxFailure::class, $marker);
    }

    public function test_illegal_transition_translates_to_a_permanent_failure(): void
    {
        $marker = $this->translator()->toInboxOutcome(IllegalChallengeTransition::from(ChallengeState::Resolved, 'adjudicate'));
        $this->assertInstanceOf(PermanentInboxFailure::class, $marker);
    }

    public function test_handlers_are_contestation_inbox_consumers(): void
    {
        $repo = new InMemoryChallengeRepository();
        $outbox = $this->outbox();
        $clock = FrozenClock::at('2026-07-08T10:04:00+00:00');
        $translator = $this->translator();

        $adjudicate = new AdjudicateChallengeHandler(new ChallengeAdjudicationReaction($repo, $outbox), $clock, $translator);
        $resolve = new ResolveChallengeHandler(new ChallengeResolutionReaction($repo, $outbox), $clock, $translator);

        $this->assertInstanceOf(InboxHandler::class, $adjudicate);
        $this->assertSame('Contestation', $adjudicate->consumerContext());
        $this->assertContains('DeterminationIssued', $adjudicate->eventTypes());

        $this->assertInstanceOf(InboxHandler::class, $resolve);
        $this->assertSame('Contestation', $resolve->consumerContext());
        $this->assertContains('ElectionCorrectionApplied', $resolve->eventTypes());
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
