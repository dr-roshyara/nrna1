<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election;

use App\Contexts\Election\Application\Port\AppliedDeterminationStore;
use App\Contexts\Election\Application\Port\ElectionExistencePort;
use App\Contexts\Election\Domain\DeterminationId;
use App\Contexts\Election\Domain\Election;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\RulingOutcome;
use App\Contexts\Election\Infrastructure\Repository\CompositeElectionRepository;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * PB-004 Step 4A.3 (RED) — the composite `ElectionRepository` reconstructs the Election
 * aggregate from TWO independent persistence sources (Aggregate Reconstruction Invariant):
 *   existence  — the ElectionExistencePort (legacy today, behind the ACL), and
 *   reaction state — the greenfield AppliedDeterminationStore (the idempotency set).
 * No single source is authoritative for the aggregate as a whole.
 *
 * These are pure composition-logic tests over in-memory doubles (no DB): behaviour, not
 * transport (ER-07). The distinction that matters constitutionally: **business absence**
 * (existence = false → null → unknown election) is NOT **infrastructure failure**
 * (existence undeterminable → the error propagates, never silently becomes "not found").
 */
final class CompositeElectionRepositoryTest extends TestCase
{
    private function at(): DateTimeImmutable
    {
        return new DateTimeImmutable('2026-07-08T10:04:00+00:00');
    }

    public function test_existing_election_with_no_prior_corrections_reconstitutes_and_first_correction_applies(): void
    {
        $repo = new CompositeElectionRepository($this->existence(true), $this->store());

        $election = $repo->find(ElectionId::fromString('election-1'));

        $this->assertInstanceOf(Election::class, $election);
        $election->applyDetermination(DeterminationId::fromString('det-1'), RulingOutcome::Upheld, $this->at());
        $this->assertCount(1, $election->pullEvents(), 'existing + empty reaction state ⇒ first correction applies');
    }

    public function test_absent_election_resolves_to_null(): void
    {
        $repo = new CompositeElectionRepository($this->existence(false), $this->store());

        $this->assertNull($repo->find(ElectionId::fromString('missing')), 'business absence ⇒ null (unknown election)');
    }

    public function test_existing_election_with_prior_determination_is_idempotent_across_reload(): void
    {
        // The reaction store already recorded det-9 for this election (a prior slice / redelivery).
        $repo = new CompositeElectionRepository($this->existence(true), $this->store(['det-9']));

        $election = $repo->find(ElectionId::fromString('election-1'));
        $this->assertInstanceOf(Election::class, $election);

        $election->applyDetermination(DeterminationId::fromString('det-9'), RulingOutcome::Upheld, $this->at());
        $this->assertSame([], $election->pullEvents(), 'a determination already in the reaction state yields no new correction');
    }

    public function test_infrastructure_failure_determining_existence_propagates_and_is_not_business_absence(): void
    {
        $exploding = new class implements ElectionExistencePort {
            public function exists(ElectionId $id): bool
            {
                throw new RuntimeException('legacy directory unavailable');
            }
        };
        $repo = new CompositeElectionRepository($exploding, $this->store());

        // Must NOT be swallowed into null (which would misclassify a transient infra fault
        // as a permanent "unknown election" and wrongly dead-letter the message).
        $this->expectException(RuntimeException::class);
        $repo->find(ElectionId::fromString('election-1'));
    }

    public function test_save_records_applied_determinations_into_the_reaction_store(): void
    {
        $store = $this->store();
        $repo = new CompositeElectionRepository($this->existence(true), $store);

        $election = $repo->find(ElectionId::fromString('election-1'));
        $this->assertInstanceOf(Election::class, $election);
        $election->applyDetermination(DeterminationId::fromString('det-7'), RulingOutcome::Upheld, $this->at());
        $election->pullEvents();
        $repo->save($election);

        $ids = array_map(
            static fn (DeterminationId $d): string => $d->toString(),
            $store->appliedDeterminations(ElectionId::fromString('election-1')),
        );
        $this->assertContains('det-7', $ids, 'save persists the aggregate\'s applied-determination set');
    }

    // ── in-memory doubles (define the intended greenfield ports) ──

    private function existence(bool $exists): ElectionExistencePort
    {
        return new class($exists) implements ElectionExistencePort {
            public function __construct(private bool $exists)
            {
            }

            public function exists(ElectionId $id): bool
            {
                return $this->exists;
            }
        };
    }

    /**
     * @param list<string> $prior
     */
    private function store(array $prior = []): AppliedDeterminationStore
    {
        $store = new class implements AppliedDeterminationStore {
            /** @var array<string, true> */
            public array $ids = [];

            /** @return list<DeterminationId> */
            public function appliedDeterminations(ElectionId $id): array
            {
                return array_map(
                    static fn (string $s): DeterminationId => DeterminationId::fromString($s),
                    array_keys($this->ids),
                );
            }

            public function remember(ElectionId $id, DeterminationId ...$determinations): void
            {
                foreach ($determinations as $determination) {
                    $this->ids[$determination->toString()] = true;
                }
            }
        };
        foreach ($prior as $id) {
            $store->ids[$id] = true;
        }

        return $store;
    }
}
