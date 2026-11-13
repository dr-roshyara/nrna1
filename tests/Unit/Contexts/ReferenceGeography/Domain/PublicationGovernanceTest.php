<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Approval;
use App\Contexts\ReferenceGeography\Domain\Assessment;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\GovernanceDecision;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\GovernanceRule;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * PBDIGIT-346 G-3c (v3.11 §8: the one independence rule, the era table, permitted combinations 1–5, the full
 * combination check, merge case A; D-84, D-88, D-89; completion §5, §5a):
 * - era A (no second steward ever): the assessing steward publishes alone; era B: a structural revision needs a counting
 *   approval (of the current version) by a person appointed when approving (steward, or matter reviewer for r) and
 *   NOT known-same under E(v) as anyone in excluded(r), re-evaluated at the publication's roster version v;
 * - a non-structural revision never needs a second person;
 * - the decision records the roster version and "independent as known to Identity at roster version v".
 *
 * Fixture note (G-3d): every decider records a conflict declaration (D-84; v3.11 §8 Checks). Declarations are roster
 * writes, so versions are taken from the roster, not hard-coded. Conflicts themselves: JudgmentMatterConflictTest.
 */
final class PublicationGovernanceTest extends TestCase
{
    private const OWNER = '0';
    private const UNIT = '3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c';

    /** A valid opaque reference for a person named in the test (any label). */
    private static function p(string $label): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $label));
    }

    private static function rid(): RevisionId
    {
        return RevisionId::fromString('9d9d9d9d-9d9d-4d9d-8d9d-9d9d9d9d9d9d');
    }

    /** Appoints the stewards, then each declares no interest in the matter. */
    private static function roster(string ...$stewards): StewardRoster
    {
        $roster = StewardRoster::empty();
        foreach ($stewards as $s) {
            $roster->appointSteward(self::p($s), self::p(self::OWNER));
        }
        self::declare($roster, ...$stewards);

        return $roster;
    }

    private static function declare(StewardRoster $roster, string ...$people): void
    {
        foreach ($people as $person) {
            $roster->declareConflict(self::p($person), self::rid(), false);
        }
    }

    private static function reader(): DependencyRecordingReader
    {
        $c = CompetentInstrument::competenceClaim();

        return new DependencyRecordingReader(new InMemoryPublishedAtlas(1, [new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, 0), 1)]));
    }

    private static function claim(bool $structural): Claim
    {
        $unit = ReferenceUnitId::fromString(self::UNIT);
        $slot = $structural ? SlotKey::existence($unit) : SlotKey::sourceName($unit, 'gazette', 'ne', 'official');

        return new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000006001'), $slot, ClaimValue::of($structural ? 'exists' : 'Koshi'), new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Instrument, 'ev-1'));
    }

    /** Prepared by $preparers (first prepares, others add evidence), assessed and submitted by $assessor. */
    private static function submitted(string $assessor, bool $structural = true, string ...$preparers): Revision
    {
        $preparers = $preparers ?: [$assessor];
        $r = Revision::prepare(self::rid(), self::p($preparers[0]));
        $r->addClaim(self::claim($structural), self::p($preparers[0]));
        foreach ($preparers as $prep) {
            $r->attachEvidence(CompetentInstrument::evidence('ev-1'), self::p($prep));
        }
        self::assessAndSubmit($r, $assessor);

        return $r;
    }

    private static function assessAndSubmit(Revision $r, string $assessor): void
    {
        foreach (AssessmentKind::required() as $kind) {
            $r->recordAssessment(new Assessment($kind, 'satisfied', self::p($assessor)));
        }
        $r->validateWith(new RevisionValidator(), self::reader());
        $r->submit(self::p($assessor));
    }

    private static function approve(Revision $r, string $by, int $rosterVersion): void
    {
        $r->approve(new Approval($r->currentVersion()->number, $rosterVersion, self::p($by)));
    }

    private static function publish(Revision $r, StewardRoster $roster, ?int $v = null): GovernanceDecision
    {
        return (new PublicationGovernance())->mayPublish($r, $roster, $v ?? $roster->version());
    }

    public function test_era_a_the_sole_steward_publishes_a_structural_change_alone(): void
    {
        $roster = self::roster('a');
        $d = self::publish(self::submitted('a'), $roster);

        $this->assertTrue($d->allowed);
        $this->assertSame(GovernanceRule::EraASolePublication, $d->rule);
        $this->assertSame($roster->version(), $d->rosterVersion);
    }

    public function test_era_b_a_structural_change_needs_an_independent_second_person(): void
    {
        $roster = self::roster('a', 'b');
        $r = self::submitted('a');

        $this->assertSame([GovernanceRefusal::SecondPersonRequired], self::publish($r, $roster)->refusals);

        self::approve($r, 'b', $roster->version());
        $d = self::publish($r, $roster);
        $this->assertTrue($d->allowed);
        $this->assertSame(GovernanceRule::SecondPersonApproved, $d->rule);
        $this->assertTrue($d->secondPerson?->equals(self::p('b')));
        $this->assertSame("independent as known to Identity at roster version {$roster->version()}", $d->independence);
    }

    public function test_a_non_structural_change_never_needs_a_second_person(): void
    {
        $d = self::publish(self::submitted('a', structural: false), self::roster('a', 'b'));

        $this->assertTrue($d->allowed);
        $this->assertSame(GovernanceRule::NonStructural, $d->rule);
    }

    public function test_the_threshold_stays_after_the_second_steward_leaves_and_a_matter_reviewer_may_approve(): void
    {
        $roster = self::roster('a', 'b');
        $roster->endSteward(self::p('b'), self::p(self::OWNER));
        $r = self::submitted('a');

        $this->assertSame([GovernanceRefusal::SecondPersonRequired], self::publish($r, $roster)->refusals, 'D-89: the sole remaining steward waits');

        $roster->appointMatterReviewer(self::p('r'), self::rid(), self::p(self::OWNER));
        self::declare($roster, 'r');
        self::approve($r, 'r', $roster->version());
        $this->assertTrue(self::publish($r, $roster)->allowed, 'case 4: a matter reviewer approves');
    }

    public function test_a_second_steward_appointed_after_submission_activates_the_threshold(): void
    {
        $roster = self::roster('a');
        $r = self::submitted('a');
        $before = $roster->version();
        $roster->appointSteward(self::p('b'), self::p(self::OWNER));

        $this->assertTrue(self::publish($r, $roster, $before)->allowed, 'before: era A');
        $this->assertSame([GovernanceRefusal::SecondPersonRequired], self::publish($r, $roster)->refusals, 'after: era B');
    }

    public function test_the_full_combination_check(): void
    {
        // case 2: P prepares, S assesses; X must differ from both
        $roster = self::roster('p', 's', 'x');
        foreach (['p' => false, 's' => false, 'x' => true] as $approver => $ok) {
            $r = self::submitted('s', true, 'p');
            self::approve($r, $approver, $roster->version());
            $this->assertSame($ok, self::publish($r, $roster)->allowed, "approver {$approver}");
        }

        // two preparers P1 = S, P2: X = P2 is a preparer
        $roster = self::roster('1', '2');
        $r = self::submitted('1', true, '1', '2');
        self::approve($r, '2', $roster->version());
        $this->assertSame([GovernanceRefusal::ApproverNotIndependent], self::publish($r, $roster)->refusals);

        // case 3: a matter reviewer R assesses; R cannot also approve
        $roster = self::roster('p', 'q');
        $roster->appointMatterReviewer(self::p('r'), self::rid(), self::p(self::OWNER));
        self::declare($roster, 'r');
        $r = self::submitted('r', true, 'p');
        self::approve($r, 'r', $roster->version());
        $this->assertSame([GovernanceRefusal::ApproverNotIndependent], self::publish($r, $roster)->refusals);
    }

    public function test_assessors_of_earlier_versions_stay_excluded_and_an_approval_binds_to_its_version(): void
    {
        $roster = self::roster('p', 's', 't', 'x');
        $r = self::submitted('s', true, 'p');           // version 1 assessed by S
        self::approve($r, 'x', $roster->version());     // approval of version 1
        $r->markStale();
        $r->reprepare(self::p('p'));
        self::assessAndSubmit($r, 't');                 // version 2 assessed by T

        $this->assertSame([GovernanceRefusal::SecondPersonRequired], self::publish($r, $roster)->refusals, 'the approval named version 1');

        self::approve($r, 's', $roster->version());
        $this->assertSame([GovernanceRefusal::ApproverNotIndependent], self::publish($r, $roster)->refusals, 'S assessed version 1 of r');
    }

    public function test_the_approver_must_have_been_appointed_when_approving(): void
    {
        $roster = self::roster('a', 'b');
        $r = self::submitted('a');
        self::approve($r, 'x', $roster->version());                  // x not appointed then
        $roster->appointSteward(self::p('x'), self::p(self::OWNER));
        self::declare($roster, 'x');

        $this->assertSame([GovernanceRefusal::ApproverNotAppointedWhenApproving], self::publish($r, $roster)->refusals);
    }

    public function test_merge_case_a_a_merge_recorded_before_publication_is_re_checked(): void
    {
        $roster = self::roster('a', 'b');
        $r = self::submitted('a');
        self::approve($r, 'b', $roster->version());
        $before = $roster->version();
        $roster->recordMerge('m-1', [self::p('a'), self::p('b')]);

        $this->assertTrue(self::publish($r, $roster, $before)->allowed, 'as known before the notice');
        $this->assertSame([GovernanceRefusal::ApproverNotIndependent], self::publish($r, $roster)->refusals, 'after it, the approver is known-same as the assessor');
    }

    public function test_merge_case_d_a_merge_not_involving_the_approver_changes_nothing(): void
    {
        $roster = self::roster('1', '2', 'x');
        $r = self::submitted('1', true, '1', '2');
        self::approve($r, 'x', $roster->version());
        $roster->recordMerge('m-1', [self::p('1'), self::p('2')]);   // the two preparers are one person

        $this->assertTrue(self::publish($r, $roster)->allowed);
    }

    public function test_submission_and_approval_need_authority_at_that_roster_version(): void
    {
        $roster = self::roster('a', 'b');
        self::declare($roster, 'z');   // z declares, but is not appointed
        $g = new PublicationGovernance();
        $v = $roster->version();
        $draft = Revision::prepare(self::rid(), self::p('a'));

        $this->assertTrue($g->maySubmit(self::p('a'), $draft, $roster, $v)->allowed);
        $this->assertSame([GovernanceRefusal::NotAppointed], $g->maySubmit(self::p('z'), $draft, $roster, $v)->refusals);

        $r = self::submitted('a');
        $this->assertTrue($g->mayApprove(self::p('b'), $r, $roster, $v)->allowed);
        $this->assertSame([GovernanceRefusal::ApproverNotIndependent], $g->mayApprove(self::p('a'), $r, $roster, $v)->refusals);
        $this->assertSame([GovernanceRefusal::NotAppointed], $g->mayApprove(self::p('z'), $r, $roster, $v)->refusals);
    }

    public function test_only_a_submitted_revision_can_be_published(): void
    {
        $this->assertSame([GovernanceRefusal::NotSubmitted], self::publish(Revision::prepare(self::rid(), self::p('a')), self::roster('a'))->refusals);
    }
}
