<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Application\Service\CoordinatesAdjudication;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\ElectionId;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Determination\TargetId;
use App\Contexts\Adjudication\Domain\Determination\TargetType;
use App\Contexts\Adjudication\Domain\Events\DeterminationIssued;
use App\Contexts\Adjudication\Domain\Exception\DeterminationAlreadyIssued;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tests\Support\Adjudication\InMemoryDeterminationRepository;
use Tests\Support\Adjudication\InMemoryEventOutbox;

final class AdjudicationServiceTest extends TestCase
{
    private InMemoryDeterminationRepository $repo;
    private InMemoryEventOutbox $outbox;
    private CoordinatesAdjudication $service;

    protected function setUp(): void
    {
        $this->repo = new InMemoryDeterminationRepository();
        $this->outbox = new InMemoryEventOutbox();
        $this->service = new CoordinatesAdjudication($this->repo, $this->outbox, new class implements \App\Contexts\Adjudication\Application\Port\IdentityGenerator {
            public function next(): string
            {
                return 'corr-minted';
            }
        });
    }

    private function command(string $challenge = 'ch-1'): IssueDeterminationCommand
    {
        return new IssueDeterminationCommand(
            ChallengeRef::fromString($challenge),
            DeterminationOutcome::Upheld,
            Legitimacy::Legitimate,
            Reason::fromString('Tally dispute upheld.'),
            IssuedByAuthority::fromString('ARB'),
            Jurisdiction::fromString('National'),
            EvidenceEnvelopeRef::fromString('ev-1'),
            ContestedOutcomeRef::of(
                ElectionId::fromString('election-1'),
                TargetType::ElectionResult,
                TargetId::fromString('result-1'),
            ),
            EvidenceSet::fromRefs('ev-1'),
            new DateTimeImmutable('2026-06-27T10:00:00+00:00'),
        );
    }

    public function test_issuing_persists_one_determination_and_enqueues_one_event(): void
    {
        $id = $this->service->issueDetermination($this->command());

        $this->assertInstanceOf(DeterminationId::class, $id);
        $this->assertSame(1, $this->repo->count(), 'exactly one aggregate written');

        $events = $this->outbox->enqueued();
        $this->assertCount(1, $events, 'exactly one event enqueued');
        $this->assertInstanceOf(DeterminationIssued::class, $events[0]);
        $this->assertSame('ch-1', $events[0]->challengeRef->toString());
        $this->assertSame($id->toString(), $events[0]->determinationId->toString());
    }

    public function test_reissue_for_same_challenge_throws_and_does_not_double_write(): void
    {
        $this->service->issueDetermination($this->command('ch-9'));

        try {
            $this->service->issueDetermination($this->command('ch-9'));
            $this->fail('Expected DeterminationAlreadyIssued');
        } catch (DeterminationAlreadyIssued) {
            $this->assertSame(1, $this->repo->count(), 'no second aggregate written');
            $this->assertCount(1, $this->outbox->enqueued(), 'no second event enqueued');
        }
    }
}
