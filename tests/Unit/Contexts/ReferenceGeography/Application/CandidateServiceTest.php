<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\ActingPersonRefused;
use App\Contexts\ReferenceGeography\Application\CandidateChangedConcurrently;
use App\Contexts\ReferenceGeography\Application\CandidateService;
use App\Contexts\ReferenceGeography\Application\GeographyActRefused;
use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\CandidateRepository;
use App\Contexts\ReferenceGeography\Application\Ports\PublishedAtlasSource;
use App\Contexts\ReferenceGeography\Application\RevisionService;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\PublishedAtlas;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;
use App\Contexts\ReferenceGeography\Application\StoredCandidate;
use App\Contexts\ReferenceGeography\Domain\Candidate;
use App\Contexts\ReferenceGeography\Domain\CandidateId;
use App\Contexts\ReferenceGeography\Domain\CandidateState;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\InMemoryRevisionRepository;
use Tests\Support\ReferenceGeography\InMemoryStewardRosterStore;

/**
 * PBDIGIT-346 G-6a, the Candidate use cases (D-31: stewards decide candidates; strategic baseline: stewards submit
 * imports; organisation submissions arrive with the Organisation integration, D-147):
 * - every act takes its actor from ActingPerson and needs a steward appointed at the roster's current version;
 * - acceptance names an existing revision (the candidate never becomes a fact by itself);
 * - a link needs the primary to exist; every save is against the row version it was loaded at.
 */
final class CandidateServiceTest extends TestCase
{
    private const C1 = '00000000-0000-4000-8000-000000000001';
    private const C2 = '00000000-0000-4000-8000-000000000002';
    private const REV = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';

    private static function p(string $who): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $who));
    }

    private static function acting(?string $who): ActingPerson
    {
        return new class ($who === null ? null : self::p($who)) implements ActingPerson {
            public function __construct(private readonly ?PersonReference $person)
            {
            }

            public function current(): PersonReference
            {
                return $this->person ?? throw new ActingPersonRefused('not-signed-in');
            }
        };
    }

    /** @return array{CandidateService, CandidateRepository, InMemoryRevisionRepository} */
    private static function world(?string $actor = 'steward'): array
    {
        $roster = new InMemoryStewardRosterStore();
        $roster->writeUnderLock(static fn (StewardRoster $r) => $r->appointSteward(self::p('steward'), self::p('owner')));
        $revisions = new InMemoryRevisionRepository();
        $revisions->add(Revision::prepare(RevisionId::fromString(self::REV), self::p('steward')));
        $candidates = new class implements CandidateRepository {
            /** @var array<string, array{\App\Contexts\ReferenceGeography\Domain\CandidateSnapshot, int}> */
            private array $rows = [];

            public function add(Candidate $candidate): void
            {
                isset($this->rows[$candidate->id()->value()]) && throw new \RuntimeException('exists');
                $this->rows[$candidate->id()->value()] = [$candidate->snapshot(), 1];
            }

            public function get(CandidateId $id): StoredCandidate
            {
                [$s, $v] = $this->rows[$id->value()] ?? throw new \OutOfBoundsException('no candidate');

                return new StoredCandidate(Candidate::fromSnapshot($s), $v);
            }

            public function save(Candidate $candidate, int $expectedRowVersion): int
            {
                if (($this->rows[$candidate->id()->value()][1] ?? null) !== $expectedRowVersion) {
                    throw new CandidateChangedConcurrently($candidate->id()->value());
                }
                $this->rows[$candidate->id()->value()] = [$candidate->snapshot(), $expectedRowVersion + 1];

                return $expectedRowVersion + 1;
            }
        };

        $acting = self::acting($actor);
        $atlas = new class implements PublishedAtlasSource {
            public function current(): PublishedAtlas
            {
                return new InMemoryPublishedAtlas(0, []);
            }
        };
        $revisionService = new RevisionService($revisions, $roster, $atlas, $acting, new PublicationGovernance(), new RevisionValidator());

        return [new CandidateService($candidates, $roster, $revisionService, $acting), $candidates, $revisions];
    }

    public function test_acceptance_proposes_a_new_revision_prepared_by_the_acting_steward(): void
    {
        [$service, $candidates, $revisions] = self::world();
        $service->submitImport(CandidateId::fromString(self::C1), 'Ward 33 created by the 2026 act', null, ['sha256:' . str_repeat('a', 64)]);
        $service->startVerification(CandidateId::fromString(self::C1));
        $proposed = RevisionId::fromString('5e5e5e5e-5e5e-4e5e-8e5e-5e5e5e5e5e5e');
        $service->accept(CandidateId::fromString(self::C1), $proposed);

        $stored = $candidates->get(CandidateId::fromString(self::C1));
        $this->assertSame(CandidateState::Accepted, $stored->candidate->state());
        $this->assertTrue($stored->candidate->revision()?->equals($proposed));
        $this->assertTrue($stored->candidate->decidedBy()?->equals(self::p('steward')), 'the actor, from ActingPerson');
        $draft = $revisions->get($proposed);
        $this->assertFalse($draft->published, 'a proposal (closure design: Accept → ProposeRevision), never a fact');
        $this->assertEquals([self::p('steward')], $draft->revision->snapshot()->preparers, 'prepared by the accepting steward');
    }

    public function test_acceptance_cannot_attach_an_existing_unrelated_revision(): void
    {
        [$service, $candidates] = self::world();
        $service->submitImport(CandidateId::fromString(self::C1), 'x', null, []);
        $service->startVerification(CandidateId::fromString(self::C1));

        try {
            $service->accept(CandidateId::fromString(self::C1), RevisionId::fromString(self::REV));   // REV already exists
            $this->fail('accepted by attaching an existing revision');
        } catch (\RuntimeException) {
            $this->assertSame(CandidateState::UnderVerification, $candidates->get(CandidateId::fromString(self::C1))->candidate->state(), 'nothing decided');
        }
    }

    public function test_an_acceptance_the_lifecycle_refuses_prepares_no_revision(): void
    {
        [$service, , $revisions] = self::world();
        $service->submitImport(CandidateId::fromString(self::C1), 'x', null, []);   // not under verification
        $proposed = RevisionId::fromString('5e5e5e5e-5e5e-4e5e-8e5e-5e5e5e5e5e5e');

        try {
            $service->accept(CandidateId::fromString(self::C1), $proposed);
            $this->fail('accepted before verification');
        } catch (\App\Contexts\ReferenceGeography\Domain\CandidateRefused) {
            $this->assertFalse($revisions->exists($proposed), 'no orphan draft');
        }
    }

    public function test_a_duplicate_is_linked_to_an_existing_primary(): void
    {
        [$service, $candidates] = self::world();
        $service->submitImport(CandidateId::fromString(self::C1), 'primary', null, []);
        $service->submitImport(CandidateId::fromString(self::C2), 'duplicate', null, []);
        $service->linkTo(CandidateId::fromString(self::C2), CandidateId::fromString(self::C1));

        $this->assertSame(CandidateState::Linked, $candidates->get(CandidateId::fromString(self::C2))->candidate->state());
    }

    public function test_someone_not_appointed_cannot_act_and_nobody_signed_in_is_refused(): void
    {
        [$stewardService] = self::world();
        $stewardService->submitImport(CandidateId::fromString(self::C1), 'x', null, []);

        foreach (['stranger', null] as $who) {
            [$service, $candidates] = self::world($who);
            try {
                $service->submitImport(CandidateId::fromString(self::C2), 'x', null, []);
                $this->fail('accepted an act by ' . ($who ?? 'nobody'));
            } catch (GeographyActRefused $e) {
                $this->assertSame([GovernanceRefusal::NotAppointed], $e->refusals);
            } catch (ActingPersonRefused) {
                $this->assertNull($who);
            }
        }
    }

    public function test_a_rejection_records_its_reason_and_reviewer(): void
    {
        [$service, $candidates] = self::world();
        $service->submitImport(CandidateId::fromString(self::C1), 'x', null, []);
        $service->reject(CandidateId::fromString(self::C1), 'No competent instrument');

        $c = $candidates->get(CandidateId::fromString(self::C1))->candidate;
        $this->assertSame(['rejected', 'No competent instrument'], [$c->state()->value, $c->reason()]);
    }
}
