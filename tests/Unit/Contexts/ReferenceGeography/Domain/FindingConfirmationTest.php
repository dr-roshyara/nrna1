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
use App\Contexts\ReferenceGeography\Domain\FindingConfirmation;
use App\Contexts\ReferenceGeography\Domain\FindingLifecycle;
use App\Contexts\ReferenceGeography\Domain\GovernanceNotice;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\JudgmentReason;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\PublishedApproval;
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
 * PBDIGIT-346 G-6c-4 (design revision 3 §7, §8.1, §8.4 (a); accepted 2026-11-14): who may confirm a D-90 finding, pure.
 * Refused unless, at the current roster version w: the finding is OPEN; the digest is the publication's content digest;
 * the confirmer may act for the matter; is not known-same as anyone in excluded(r) nor as the counted approver whose
 * defect the finding records; and has a D-84a declaration without a material conflict. Also: the counted approver of a
 * publication is replayed from mayPublish at its stored roster version (§17).
 */
final class FindingConfirmationTest extends TestCase
{
    private const PUB = 5;
    private const F = 9;

    private static function p(string $label): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $label));
    }

    private static function rid(): RevisionId
    {
        return RevisionId::fromString('9e9e9e9e-9e9e-4e9e-8e9e-9e9e9e9e9e9e');
    }

    /** Stewards a (assessor, so excluded), x (approver), c and d; all declare no interest in the matter. */
    private static function roster(): StewardRoster
    {
        $r = StewardRoster::empty();
        foreach (['a', 'x', 'c', 'd'] as $s) {
            $r->appointSteward(self::p($s), self::p('owner'));
        }
        foreach (['a', 'x', 'c'] as $s) {
            $r->declareConflict(self::p($s), self::rid(), false);
        }

        return $r;
    }

    /** Assessed and submitted by a, approved by x: a published second-person revision. */
    private static function revision(StewardRoster $roster, bool $judgment = false, ?string $evidenceBy = null): Revision
    {
        $c = CompetentInstrument::competenceClaim();
        $reader = new DependencyRecordingReader(new InMemoryPublishedAtlas(1, [new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, 0), 1)]));
        $r = Revision::prepare(self::rid(), self::p('a'));
        $r->addClaim(new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000006c41'), SlotKey::existence(ReferenceUnitId::fromString('6c4c6c4c-6c4c-4c4c-8c4c-6c4c6c4c6c4c')),
            ClaimValue::of('exists'), new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Instrument, 'ev-1')), self::p('a'));
        $r->attachEvidence(CompetentInstrument::evidence('ev-1'), self::p('a'));
        if ($evidenceBy !== null) {
            $r->attachEvidence(CompetentInstrument::evidence('ev-1'), self::p($evidenceBy));   // a second preparer: excluded
        }
        if ($judgment) {
            $r->flagJudgmentMatter(self::p('a'), JudgmentReason::UncertainClassification);
        }
        foreach (AssessmentKind::required() as $kind) {
            $r->recordAssessment(new Assessment($kind, 'satisfied', self::p('a')));
        }
        $r->validateWith(new RevisionValidator(), $reader);
        $r->submit(self::p('a'));
        $r->approve(new Approval($r->currentVersion()->number, $roster->version(), self::p('x')));

        return $r;
    }

    private static function open(): FindingLifecycle
    {
        return FindingLifecycle::of(self::PUB, [[self::F, GovernanceNotice::finding(self::PUB, ['m1'], 1)]], []);
    }

    /** @return list<GovernanceRefusal> */
    private static function refusals(string $confirmer, StewardRoster $roster, Revision $r, int $v0, ?FindingLifecycle $lifecycle = null, ?string $digest = null, int $finding = self::F): array
    {
        $g = new PublicationGovernance();
        $approval = $g->publishedApproval(self::PUB, $r, $roster, $v0);

        return FindingConfirmation::decide($g, self::p($confirmer), $finding, $digest ?? $approval->digest, $approval, $lifecycle ?? self::open(), $r, $roster, $roster->version())->refusals;
    }

    /** The published state, then x is found to be a (the defect the finding records). */
    private static function defective(bool $judgment = false): array
    {
        $roster = self::roster();
        $r = self::revision($roster, $judgment);
        $v0 = $roster->version();
        $roster->recordMerge('m1', [self::p('x'), self::p('a')]);

        return [$roster, $r, $v0];
    }

    public function test_the_counted_approver_is_replayed_at_the_publication_roster_version(): void
    {
        [$roster, $r, $v0] = self::defective();
        $approval = (new PublicationGovernance())->publishedApproval(self::PUB, $r, $roster, $v0);

        $this->assertTrue($approval->approver->equals(self::p('x')), 'replayed at v0, before the merge: x counted');
        $this->assertSame([self::PUB, $r->currentVersion()->number, $r->currentVersion()->digest], [$approval->position, $approval->contentVersion, $approval->digest]);
        $this->expectException(\LogicException::class);
        (new PublicationGovernance())->publishedApproval(self::PUB, $r, $roster, $roster->version());   // at w x is not independent: no second person
    }

    public function test_an_independent_eligible_steward_confirms_an_open_finding(): void
    {
        [$roster, $r, $v0] = self::defective();
        $this->assertSame([], self::refusals('c', $roster, $r, $v0));
    }

    public function test_the_defective_approver_and_anyone_known_same_as_them_are_refused(): void
    {
        [$roster, $r, $v0] = self::defective();
        $roster->declareConflict(self::p('d'), self::rid(), false);
        $roster->recordMerge('m2', [self::p('d'), self::p('x')]);   // d turns out to be x… who is a: excluded as well

        $this->assertContains(GovernanceRefusal::ConfirmerNotIndependent, self::refusals('x', $roster, $r, $v0));
        $this->assertContains(GovernanceRefusal::ConfirmerNotIndependent, self::refusals('d', $roster, $r, $v0));
    }

    public function test_the_defective_approver_alone_is_refused_even_when_not_excluded(): void
    {
        // the counted approver whose defect f records, by itself: after a confirmation by c, a NEW finding records c's
        // defect (§8.4 (a)); c is not excluded, yet c may not confirm its own defect
        [$roster, $r, $v0] = self::defective();
        $episode2 = FindingLifecycle::of(self::PUB, [[self::F, GovernanceNotice::finding(self::PUB, ['m1'], 1)],
            [10, GovernanceNotice::confirmation(self::F, self::p('c'), 'd', 2)], [12, GovernanceNotice::finding(self::PUB, ['m3'], 3)]], []);

        $this->assertSame([GovernanceRefusal::ConfirmerNotIndependent], self::refusals('c', $roster, $r, $v0, $episode2, finding: 12));
    }

    public function test_an_excluded_person_is_refused(): void
    {
        [$roster, $r, $v0] = self::defective();
        $this->assertContains(GovernanceRefusal::ConfirmerNotIndependent, self::refusals('a', $roster, $r, $v0));
    }

    public function test_an_excluded_person_unrelated_to_the_defective_approver_is_refused_by_exclusion_alone(): void
    {
        // e only attached evidence (a preparer, so in excluded(r)); e is NOT known-same as x: only the exclusion refuses e
        $roster = self::roster();
        $roster->declareConflict(self::p('e'), self::rid(), false);
        $roster->appointSteward(self::p('e'), self::p('owner'));
        $r = self::revision($roster, evidenceBy: 'e');
        $v0 = $roster->version();
        $roster->recordMerge('m1', [self::p('x'), self::p('a')]);

        $this->assertFalse($roster->knownSameAt(self::p('e'), self::p('x'), $roster->version()));
        $this->assertSame([GovernanceRefusal::ConfirmerNotIndependent], self::refusals('e', $roster, $r, $v0));
    }

    public function test_a_person_not_appointed_for_the_matter_is_refused(): void
    {
        [$roster, $r, $v0] = self::defective();
        $roster->declareConflict(self::p('outsider'), self::rid(), false);
        $this->assertSame([GovernanceRefusal::NotAppointed], self::refusals('outsider', $roster, $r, $v0));
    }

    public function test_a_missing_declaration_and_a_material_conflict_are_refused(): void
    {
        [$roster, $r, $v0] = self::defective();
        $this->assertSame([GovernanceRefusal::ConflictDeclarationMissing], self::refusals('d', $roster, $r, $v0), 'd made no declaration');

        [$roster, $r, $v0] = self::defective(judgment: true);
        $roster->declareConflict(self::p('d'), self::rid(), true);
        $this->assertSame([GovernanceRefusal::MateriallyConflicted], self::refusals('d', $roster, $r, $v0), 'an interest in a judgment matter');
    }

    public function test_a_digest_other_than_the_published_content_is_refused(): void
    {
        [$roster, $r, $v0] = self::defective();
        $this->assertSame([GovernanceRefusal::DigestMismatch], self::refusals('c', $roster, $r, $v0, digest: str_repeat('0', 64)));
    }

    public function test_only_an_open_finding_can_be_confirmed(): void
    {
        [$roster, $r, $v0] = self::defective();
        $finding = [self::F, GovernanceNotice::finding(self::PUB, ['m1'], 1)];
        $cases = [
            'closed' => FindingLifecycle::of(self::PUB, [$finding, [10, GovernanceNotice::closure(self::F, 'reversal:m1', 2)]], []),
            'confirmed already' => FindingLifecycle::of(self::PUB, [$finding, [10, GovernanceNotice::confirmation(self::F, self::p('d'), 'd', 2)]], []),
            'corrected by a published revision' => FindingLifecycle::of(self::PUB, [$finding], [11]),
        ];
        foreach ($cases as $why => $lifecycle) {
            $this->assertSame([GovernanceRefusal::FindingNotOpen], self::refusals('c', $roster, $r, $v0, $lifecycle), $why);
        }
        $this->assertSame([GovernanceRefusal::FindingNotOpen], self::refusals('c', $roster, $r, $v0, finding: 7), 'not a finding of this publication');
    }
}
