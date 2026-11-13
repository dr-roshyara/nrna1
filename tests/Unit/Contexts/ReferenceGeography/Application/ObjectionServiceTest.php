<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\GeographyActRefused;
use App\Contexts\ReferenceGeography\Application\ObjectionChangedConcurrently;
use App\Contexts\ReferenceGeography\Application\ObjectionService;
use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\ObjectionRepository;
use App\Contexts\ReferenceGeography\Application\Ports\PublishedAtlasSource;
use App\Contexts\ReferenceGeography\Application\Ports\PublishedClaims;
use App\Contexts\ReferenceGeography\Application\RevisionService;
use App\Contexts\ReferenceGeography\Application\StoredObjection;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\Objection;
use App\Contexts\ReferenceGeography\Domain\ObjectionId;
use App\Contexts\ReferenceGeography\Domain\ObjectionRefused;
use App\Contexts\ReferenceGeography\Domain\ObjectionState;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\PublishedAtlas;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;
use Tests\Support\ReferenceGeography\InMemoryRevisionRepository;
use Tests\Support\ReferenceGeography\InMemoryStewardRosterStore;

/**
 * PBDIGIT-346 G-6b, the Objection use cases (strategic baseline S1.1; D-84; D-84a):
 * - a steward records an organisation's objection on its behalf; the target must be a PUBLISHED claim;
 * - reconsideration only by a new objection with NEW evidence: an objection citing nothing not already cited against
 *   that claim is refused;
 * - correction and dispute PROPOSE a new revision through RevisionService (the claim changes only through it);
 * - the actor comes from ActingPerson and is a steward at the current roster version; D-84a in the domain.
 */
final class ObjectionServiceTest extends TestCase
{
    private const ORG = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const CLAIM = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const O1 = '00000000-0000-4000-8000-000000000001';
    private const O2 = '00000000-0000-4000-8000-000000000002';
    private const REV = '5e5e5e5e-5e5e-4e5e-8e5e-5e5e5e5e5e5e';

    private static function p(string $who): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $who));
    }

    /** @return array{\Closure(string): ObjectionService, ObjectionRepository, InMemoryRevisionRepository} */
    private static function world(): array
    {
        $roster = new InMemoryStewardRosterStore();
        $roster->writeUnderLock(static function (StewardRoster $r): void {
            $r->appointSteward(self::p('recorder'), self::p('owner'));
            $r->appointSteward(self::p('assessor'), self::p('owner'));
        });
        $revisions = new InMemoryRevisionRepository();
        $objections = new class implements ObjectionRepository {
            /** @var array<string, array{\App\Contexts\ReferenceGeography\Domain\ObjectionSnapshot, int}> */
            private array $rows = [];

            public function add(Objection $objection): void
            {
                isset($this->rows[$objection->id()->value()]) && throw new \RuntimeException('exists');
                $this->rows[$objection->id()->value()] = [$objection->snapshot(), 1];
            }

            public function get(ObjectionId $id): StoredObjection
            {
                [$s, $v] = $this->rows[$id->value()] ?? throw new \OutOfBoundsException('none');

                return new StoredObjection(Objection::fromSnapshot($s), $v);
            }

            public function save(Objection $objection, int $expectedRowVersion): int
            {
                if (($this->rows[$objection->id()->value()][1] ?? null) !== $expectedRowVersion) {
                    throw new ObjectionChangedConcurrently($objection->id()->value());
                }
                $this->rows[$objection->id()->value()] = [$objection->snapshot(), $expectedRowVersion + 1];

                return $expectedRowVersion + 1;
            }

            public function evidenceCitedAgainst(ClaimId $target): array
            {
                $cited = [];
                foreach ($this->rows as [$s]) {
                    if ($s->target->equals($target)) {
                        array_push($cited, ...$s->evidence);
                    }
                }

                return array_values(array_unique($cited));
            }
        };
        $published = new class implements PublishedClaims {
            public function isPublished(ClaimId $claim): bool
            {
                return $claim->value() === ObjectionServiceTest::publishedClaim();
            }
        };
        $atlas = new class implements PublishedAtlasSource {
            public function current(): PublishedAtlas
            {
                return new InMemoryPublishedAtlas(0, []);
            }
        };
        $as = static function (string $who) use ($objections, $roster, $revisions, $published, $atlas): ObjectionService {
            $acting = new class (self::p($who)) implements ActingPerson {
                public function __construct(private readonly PersonReference $person)
                {
                }

                public function current(): PersonReference
                {
                    return $this->person;
                }
            };

            return new ObjectionService($objections, $roster, new RevisionService($revisions, $roster, $atlas, $acting, new PublicationGovernance(), new RevisionValidator()), $published, $acting);
        };

        return [$as, $objections, $revisions];
    }

    public static function publishedClaim(): string
    {
        return self::CLAIM;
    }

    public function test_an_objection_is_recorded_assessed_and_corrected_by_proposing_a_revision(): void
    {
        [$as, $objections, $revisions] = self::world();
        $as('recorder')->submitOnBehalf(ObjectionId::fromString(self::O1), self::ORG, ClaimId::fromString(self::CLAIM), 'moved to Lalitpur', ['sha256:' . str_repeat('b', 64)]);
        $as('assessor')->assess(ObjectionId::fromString(self::O1), true, true, true);
        $as('assessor')->correct(ObjectionId::fromString(self::O1), RevisionId::fromString(self::REV));

        $this->assertSame(ObjectionState::Corrected, $objections->get(ObjectionId::fromString(self::O1))->objection->state());
        $this->assertFalse($revisions->get(RevisionId::fromString(self::REV))->published, 'a proposal; the claim is unchanged until it is published');
    }

    public function test_only_a_published_claim_can_be_objected_to(): void
    {
        [$as] = self::world();

        $this->expectException(\OutOfBoundsException::class);
        $as('recorder')->submitOnBehalf(ObjectionId::fromString(self::O1), self::ORG, ClaimId::fromString('9f9f9f9f-9f9f-4f9f-8f9f-9f9f9f9f9f9f'), 'x', ['sha256:x']);
    }

    public function test_reconsideration_needs_new_evidence(): void
    {
        [$as] = self::world();
        $as('recorder')->submitOnBehalf(ObjectionId::fromString(self::O1), self::ORG, ClaimId::fromString(self::CLAIM), 'first', ['sha256:old']);
        $as('assessor')->assess(ObjectionId::fromString(self::O1), true, false, true);
        $as('assessor')->reject(ObjectionId::fromString(self::O1), 'no mandate');

        try {
            $as('recorder')->submitOnBehalf(ObjectionId::fromString(self::O2), self::ORG, ClaimId::fromString(self::CLAIM), 'again', ['sha256:old']);
            $this->fail('the same evidence was objected again');
        } catch (ObjectionRefused) {
            $this->addToAssertionCount(1);
        }
        $as('recorder')->submitOnBehalf(ObjectionId::fromString(self::O2), self::ORG, ClaimId::fromString(self::CLAIM), 'again, with the 2027 gazette', ['sha256:old', 'sha256:new']);
        $this->addToAssertionCount(1);
    }

    public function test_someone_not_appointed_cannot_act_and_the_recorder_cannot_decide(): void
    {
        [$as] = self::world();
        try {
            $as('stranger')->submitOnBehalf(ObjectionId::fromString(self::O1), self::ORG, ClaimId::fromString(self::CLAIM), 'x', ['sha256:x']);
            $this->fail('a stranger recorded an objection');
        } catch (GeographyActRefused $e) {
            $this->assertSame([GovernanceRefusal::NotAppointed], $e->refusals);
        }
        $as('recorder')->submitOnBehalf(ObjectionId::fromString(self::O1), self::ORG, ClaimId::fromString(self::CLAIM), 'x', ['sha256:x']);

        $this->expectException(ObjectionRefused::class);
        $as('recorder')->assess(ObjectionId::fromString(self::O1), true, true, true);
    }

    public function test_an_outcome_the_lifecycle_refuses_prepares_no_revision(): void
    {
        [$as, , $revisions] = self::world();
        $as('recorder')->submitOnBehalf(ObjectionId::fromString(self::O1), self::ORG, ClaimId::fromString(self::CLAIM), 'x', ['sha256:x']);

        try {
            $as('assessor')->dispute(ObjectionId::fromString(self::O1), RevisionId::fromString(self::REV));   // not assessed
            $this->fail('disputed before assessment');
        } catch (ObjectionRefused) {
            $this->assertFalse($revisions->exists(RevisionId::fromString(self::REV)), 'no orphan draft');
        }
    }
}
