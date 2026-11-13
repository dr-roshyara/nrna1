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
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\JudgmentReason;
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
 * PBDIGIT-346 G-3d (D-84, D-84a; v3.11 §8 "Conflict of interest by role", "Checks"; Principal Q-G3-1 = D-230):
 * - every decider (assessing steward, second person, matter reviewer) records a conflict declaration for the matter;
 *   publication re-checks it at its roster version;
 * - material conflict = a declared organisational interest AND a judgment matter; a routine matter needs only the
 *   declaration;
 * - a judgment matter is derived from visible triggers (a DISPUTE claim, a precedence assessment) or flagged by anyone
 *   involved; a flag is permanent: it survives re-preparation and NO operation removes it;
 * - no eligible independent decider ⇒ refused (the matter waits); a matter reviewer appointed by the platform owner
 *   for that matter, declaring no interest, may decide it (D-84a).
 */
final class JudgmentMatterConflictTest extends TestCase
{
    private const OWNER = 'owner';
    private const UNIT = '3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c';

    private static function p(string $label): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $label));
    }

    private static function rid(): RevisionId
    {
        return RevisionId::fromString('9d9d9d9d-9d9d-4d9d-8d9d-9d9d9d9d9d9d');
    }

    private static function reader(): DependencyRecordingReader
    {
        $c = CompetentInstrument::competenceClaim();

        return new DependencyRecordingReader(new InMemoryPublishedAtlas(1, [new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, 0), 1)]));
    }

    private static function existence(): Claim
    {
        return new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000007001'), SlotKey::existence(ReferenceUnitId::fromString(self::UNIT)), ClaimValue::of('exists'), new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Instrument, 'ev-1'));
    }

    private static function dispute(): Claim
    {
        return new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000007002'), SlotKey::dispute(CompetentInstrument::competenceClaim()->id), ClaimValue::of('disputed'), new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Instrument, 'ev-1'));
    }

    private static function draft(string $preparer, Claim ...$claims): Revision
    {
        $r = Revision::prepare(self::rid(), self::p($preparer));
        foreach ($claims ?: [self::existence()] as $c) {
            $r->addClaim($c, self::p($preparer));
        }
        $r->attachEvidence(CompetentInstrument::evidence('ev-1'), self::p($preparer));

        return $r;
    }

    private static function submit(Revision $r, string $assessor, bool $precedence = false): Revision
    {
        foreach ([...AssessmentKind::required(), ...($precedence ? [AssessmentKind::Precedence] : [])] as $kind) {
            $r->recordAssessment(new Assessment($kind, 'satisfied', self::p($assessor)));
        }
        $r->validateWith(new RevisionValidator(), self::reader());
        $r->submit(self::p($assessor));

        return $r;
    }

    /** @param array<string, bool> $declarations person => has a direct organisational interest */
    private static function roster(array $stewards, array $declarations = []): StewardRoster
    {
        $roster = StewardRoster::empty();
        foreach ($stewards as $s) {
            $roster->appointSteward(self::p($s), self::p(self::OWNER));
        }
        foreach ($declarations as $person => $interest) {
            $roster->declareConflict(self::p((string) $person), self::rid(), $interest);
        }

        return $roster;
    }

    private static function publish(Revision $r, StewardRoster $roster): array
    {
        return (new PublicationGovernance())->mayPublish($r, $roster, $roster->version())->refusals;
    }

    public function test_a_matter_is_a_judgment_matter_from_visible_triggers_or_a_flag(): void
    {
        $this->assertFalse(self::draft('a')->isJudgmentMatter());
        $this->assertTrue(self::draft('a', self::existence(), self::dispute())->isJudgmentMatter(), 'a DISPUTE claim');
        $this->assertTrue(self::submit(self::draft('a'), 'a', precedence: true)->isJudgmentMatter(), 'a precedence assessment');

        $flagged = self::draft('a');
        $flagged->flagJudgmentMatter(self::p('x'), JudgmentReason::CompetingInstruments);
        $this->assertTrue($flagged->isJudgmentMatter(), 'flagged by anyone involved');
    }

    public function test_a_flag_is_permanent_across_versions_and_cannot_be_removed(): void
    {
        $r = self::draft('a');
        $r->flagJudgmentMatter(self::p('a'), JudgmentReason::UncertainClassification);
        self::submit($r, 'a');
        $r->markStale();
        $r->reprepare(self::p('a'));

        $this->assertTrue($r->isJudgmentMatter(), 'survives re-preparation');
        foreach (get_class_methods(Revision::class) as $method) {
            $this->assertDoesNotMatchRegularExpression('/(unflag|unmark|clear|remove)judgment/i', $method, 'no operation removes a judgment classification');
        }
    }

    public function test_every_decider_needs_a_conflict_declaration_for_the_matter(): void
    {
        $g = new PublicationGovernance();
        $roster = self::roster(['a']);

        $this->assertSame([GovernanceRefusal::ConflictDeclarationMissing], $g->maySubmit(self::p('a'), self::draft('a'), $roster, 1)->refusals);
        $this->assertSame([GovernanceRefusal::ConflictDeclarationMissing], self::publish(self::submit(self::draft('a'), 'a'), $roster), 'era A: D-84 "with a recorded conflict-of-interest declaration"');
        $this->assertSame([], self::publish(self::submit(self::draft('a'), 'a'), self::roster(['a'], ['a' => false])));
    }

    public function test_a_declared_interest_bars_deciding_only_a_judgment_matter(): void
    {
        $roster = self::roster(['a'], ['a' => true]);

        $this->assertSame([], self::publish(self::submit(self::draft('a'), 'a'), $roster), 'routine: the declaration suffices (D-84a)');
        $this->assertSame([GovernanceRefusal::MateriallyConflicted], self::publish(self::submit(self::draft('a', self::existence(), self::dispute()), 'a'), $roster), 'the conflicted assessor cannot call a dispute routine');
        $this->assertSame([GovernanceRefusal::MateriallyConflicted], (new PublicationGovernance())->maySubmit(self::p('a'), self::draft('a', self::existence(), self::dispute()), $roster, $roster->version())->refusals);
    }

    public function test_with_no_eligible_independent_decider_the_matter_waits_until_a_matter_reviewer_decides_it(): void
    {
        // era A, the sole steward has an interest in a judgment matter: refused (held)
        $roster = self::roster(['a'], ['a' => true]);
        $this->assertSame([GovernanceRefusal::MateriallyConflicted], self::publish(self::submit(self::draft('a', self::existence(), self::dispute()), 'a'), $roster));

        // D-84a: the platform owner appoints a matter reviewer for this matter alone, who declares no interest and assesses
        $roster->appointMatterReviewer(self::p('r'), self::rid(), self::p(self::OWNER));
        $roster->declareConflict(self::p('r'), self::rid(), false);
        $this->assertSame([], self::publish(self::submit(self::draft('a', self::existence(), self::dispute()), 'r'), $roster), 'case 5: the conflicted steward prepares, the reviewer decides');
    }

    public function test_the_second_persons_conflict_is_re_checked_at_publication(): void
    {
        $roster = self::roster(['a', 'b'], ['a' => false, 'b' => false]);
        $r = self::submit(self::draft('a', self::existence(), self::dispute()), 'a');
        $r->approve(new Approval($r->currentVersion()->number, $roster->version(), self::p('b')));
        $this->assertSame([], self::publish($r, $roster));

        $roster->declareConflict(self::p('b'), self::rid(), true);   // b later declares an interest
        $this->assertSame([GovernanceRefusal::MateriallyConflicted], self::publish($r, $roster));
    }

    public function test_the_conflict_test_runs_for_the_assessor_of_the_current_version(): void
    {
        $roster = self::roster(['a', 's1', 's2'], ['s1' => true, 's2' => false, 'x' => false]);
        $r = self::submit(self::draft('a', self::existence(), self::dispute()), 's1');
        $r->markStale();
        $r->reprepare(self::p('a'));
        self::submit($r, 's2');

        $this->assertTrue($r->assessor()?->equals(self::p('s2')));
        $this->assertSame([GovernanceRefusal::SecondPersonRequired], self::publish($r, $roster), 's1 no longer decides this version; era B still needs a second person');
    }
}
