<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\ActingPersonRefused;
use App\Contexts\ReferenceGeography\Application\GeographyActRefused;
use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\PublishedAtlasSource;
use App\Contexts\ReferenceGeography\Application\PublishRevision;
use App\Contexts\ReferenceGeography\Application\RevisionAlreadyPublished;
use App\Contexts\ReferenceGeography\Application\RevisionService;
use App\Contexts\ReferenceGeography\Application\StewardRosterService;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\JudgmentReason;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\PublishedAtlas;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionState;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;
use Tests\Support\ReferenceGeography\InMemoryRevisionRepository;
use Tests\Support\ReferenceGeography\InMemoryStewardRosterStore;

/**
 * PBDIGIT-346 G-4f, the revision lifecycle use cases (v3.11 §8 roles; D-84; G-3 governance; G-4 interpretation B-9):
 * - the actor is ALWAYS the ActingPerson; no act accepts a person as its actor (no impersonation through the API);
 * - every act needs the actor appointed for the matter at the roster's current version (preparer: "any steward");
 * - submission and approval go through PublicationGovernance::maySubmit / mayApprove; the approval records the roster
 *   version it was given under;
 * - a revision with a published version is closed: every act is refused (corrections are new revisions,
 *   forward-only); a new revision for the same facts is the correction route.
 */
final class RevisionServiceTest extends TestCase
{
    private InMemoryRevisionRepository $repo;
    private InMemoryStewardRosterStore $roster;
    private string $actor = 'p';

    protected function setUp(): void
    {
        $this->repo = new InMemoryRevisionRepository();
        $this->roster = new InMemoryStewardRosterStore();
        $this->roster->writeUnderLock(static function (StewardRoster $r): void {
            foreach (['p', 'x'] as $s) {
                $r->appointSteward(self::p($s), self::p('owner'));
            }
        });
    }

    private static function p(string $label): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $label));
    }

    private static function rid(string $label = 'r'): RevisionId
    {
        $h = md5('revision:' . $label);

        return RevisionId::fromString(substr($h, 0, 8) . '-' . substr($h, 8, 4) . '-4' . substr($h, 13, 3) . '-8' . substr($h, 17, 3) . '-' . substr($h, 20, 12));
    }

    private function service(): RevisionService
    {
        $acting = new class ($this) implements ActingPerson {
            public function __construct(private readonly RevisionServiceTest $test)
            {
            }

            public function current(): PersonReference
            {
                return $this->test->actingNow();
            }
        };
        $atlas = new class implements PublishedAtlasSource {
            public function current(): PublishedAtlas
            {
                $c = CompetentInstrument::competenceClaim();

                return new InMemoryPublishedAtlas(1, [new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, 0), 1)]);
            }
        };

        return new RevisionService($this->repo, $this->roster, $atlas, $acting, new PublicationGovernance(), new RevisionValidator());
    }

    public function actingNow(): PersonReference
    {
        return $this->actor === '' ? throw new ActingPersonRefused('not-signed-in') : self::p($this->actor);
    }

    private function as(string $actor): RevisionService
    {
        $this->actor = $actor;

        return $this->service();
    }

    private function declare(string $person, RevisionId $id): void
    {
        $this->roster->writeUnderLock(static fn (StewardRoster $r): int => $r->declareConflict(self::p($person), $id, false));
    }

    private static function claim(): Claim
    {
        return new Claim(ClaimId::fromString('00000000-0000-4000-8000-00000000f001'), SlotKey::existence(ReferenceUnitId::fromString('3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c')), ClaimValue::of('exists'),
            new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Instrument, 'act'));
    }

    /** Prepared and assessed by p, submitted by p, approved by x. */
    private function submittedAndApproved(RevisionId $id): void
    {
        $this->declare('p', $id);
        $this->declare('x', $id);
        $s = $this->as('p');
        $s->prepare($id);
        $s->addClaim($id, self::claim());
        $s->attachEvidence($id, CompetentInstrument::evidence('act'));
        foreach (AssessmentKind::required() as $kind) {
            $s->recordAssessment($id, $kind, 'satisfied');
        }
        $this->assertTrue($s->validate($id)->isValid());
        $s->submit($id);
        $this->as('x')->approve($id);
    }

    public function test_the_lifecycle_runs_with_the_acting_person_as_actor_throughout(): void
    {
        $id = self::rid();
        $this->submittedAndApproved($id);

        $r = $this->repo->get($id)->revision;
        $this->assertSame(RevisionState::Submitted, $r->state());
        $this->assertTrue($r->assessor()?->equals(self::p('p')));
        $this->assertTrue($r->countingApprovals()[0]->by->equals(self::p('x')));
        $this->assertSame($this->roster->current()->version(), $r->countingApprovals()[0]->rosterVersion, 'the approval names the roster version it was given under');
    }

    public function test_no_act_accepts_a_person_as_its_actor(): void
    {
        foreach ([RevisionService::class, PublishRevision::class] as $class) {
            foreach ((new \ReflectionClass($class))->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                foreach ($method->getParameters() as $parameter) {
                    $this->assertNotSame(PersonReference::class, (string) $parameter->getType(), "{$class}::{$method->getName()}(\${$parameter->getName()}) would let a caller name the actor");
                }
            }
        }
        // the roster service names a SUBJECT (the steward to appoint), never the actor; its actor is ActingPerson too
        foreach ((new \ReflectionClass(StewardRosterService::class))->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($method->getParameters() as $parameter) {
                if ((string) $parameter->getType() === PersonReference::class) {
                    $this->assertContains($parameter->getName(), ['steward', 'reviewer'], "{$method->getName()}: only the subject of an appointment is a parameter");
                }
            }
        }
    }

    public function test_every_act_needs_an_actor_appointed_for_the_matter(): void
    {
        $this->expectException(GeographyActRefused::class);
        try {
            $this->as('stranger')->prepare(self::rid());
        } catch (GeographyActRefused $e) {
            $this->assertSame([GovernanceRefusal::NotAppointed], $e->refusals);
            throw $e;
        }
    }

    public function test_without_an_established_acting_person_nothing_happens(): void
    {
        $this->expectException(ActingPersonRefused::class);
        $this->as('')->prepare(self::rid());
    }

    public function test_submission_and_approval_go_through_governance(): void
    {
        $id = self::rid();
        $s = $this->as('p');
        $s->prepare($id);
        $s->addClaim($id, self::claim());
        $s->attachEvidence($id, CompetentInstrument::evidence('act'));
        foreach (AssessmentKind::required() as $kind) {
            $s->recordAssessment($id, $kind, 'satisfied');
        }
        $s->validate($id);

        try {
            $s->submit($id);
            $this->fail('no conflict declaration: refused');
        } catch (GeographyActRefused $e) {
            $this->assertSame([GovernanceRefusal::ConflictDeclarationMissing], $e->refusals);
        }
        $this->declare('p', $id);
        $s->submit($id);
        try {
            $this->as('p')->approve($id);
            $this->fail('the assessor approving: refused');
        } catch (GeographyActRefused $e) {
            $this->assertContains(GovernanceRefusal::ApproverNotIndependent, $e->refusals);
        }
        $this->assertSame([], $this->repo->get($id)->revision->countingApprovals());
    }

    public function test_a_published_revision_is_closed_to_every_act(): void
    {
        $id = self::rid();
        $this->submittedAndApproved($id);
        $this->repo->markPublished($id);
        $s = $this->as('p');

        foreach ([
            'reprepare' => fn () => $s->reprepare($id),
            'withdraw' => fn () => $s->withdraw($id),
            'add a claim' => fn () => $s->addClaim($id, self::claim()),
            'record an assessment' => fn () => $s->recordAssessment($id, AssessmentKind::Authenticity, 'again'),
            'flag' => fn () => $s->flagJudgmentMatter($id, JudgmentReason::Dispute),
            'approve' => fn () => $this->as('x')->approve($id),
        ] as $act => $call) {
            try {
                $call();
                $this->fail("must be refused: {$act}");
            } catch (RevisionAlreadyPublished) {
                $this->addToAssertionCount(1);
            }
        }
        $this->assertSame(RevisionState::Submitted, $this->repo->get($id)->revision->state());
    }

    public function test_a_correction_is_a_new_revision_forward_only(): void
    {
        $id = self::rid('original');
        $this->submittedAndApproved($id);
        $this->repo->markPublished($id);

        $correction = self::rid('correction');
        $this->declare('p', $correction);
        $s = $this->as('p');
        $s->prepare($correction);
        $s->addClaim($correction, new Claim(ClaimId::fromString('00000000-0000-4000-8000-00000000f002'), self::claim()->slot, ClaimValue::none(), new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Correction, 'corr-1')));

        $this->assertCount(1, $this->repo->get($correction)->revision->claims(), 'the correction is its own revision');
        $this->assertSame(RevisionState::Submitted, $this->repo->get($id)->revision->state(), 'the published one is untouched');
    }
}
