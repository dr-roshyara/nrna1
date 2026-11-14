<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\CorrectionCoverage;
use App\Contexts\ReferenceGeography\Domain\FindingLifecycle;
use App\Contexts\ReferenceGeography\Domain\GovernanceNotice;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-6c-5 (design revision 3 §8, §9; Principal decision 1, 2026-11-14: FULL COVERAGE): a correction resolves
 * a D-90 finding only if, for EVERY claim c of the flagged publication, the correcting revision's claims in c's slot
 * together cover c's whole valid interval [from, to) (to exclusive; no `to` = open-ended). Several adjacent claims in a
 * slot may cover jointly (a revision permits non-overlapping claims per slot). The link needs an OPEN finding.
 */
final class CorrectionCoverageTest extends TestCase
{
    private const UNIT = '6c5c6c5c-6c5c-4c5c-8c5c-6c5c6c5c6c5c';
    private static int $n = 0;

    private static function claim(string $from, ?string $to = null, ?SlotKey $slot = null): Claim
    {
        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', ++self::$n)), $slot ?? self::slot(),
            ClaimValue::of('exists'), new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to)), new Basis(BasisKind::Instrument, 'ev'));
    }

    private static function slot(string $unit = self::UNIT): SlotKey
    {
        return SlotKey::existence(ReferenceUnitId::fromString($unit));
    }

    /** @param list<Claim> $flagged @param list<Claim> $replacements */
    private static function covered(array $flagged, array $replacements): bool
    {
        return CorrectionCoverage::uncovered($flagged, $replacements) === [];
    }

    public function test_one_replacement_covering_the_whole_interval(): void
    {
        $this->assertTrue(self::covered([self::claim('2016-01-01', '2020-01-01')], [self::claim('2016-01-01', '2020-01-01')]), 'exact');
        $this->assertTrue(self::covered([self::claim('2016-01-01', '2020-01-01')], [self::claim('2010-01-01', '2030-01-01')]), 'wider');
    }

    public function test_several_adjacent_replacements_cover_jointly(): void
    {
        $this->assertTrue(self::covered([self::claim('2016-01-01', '2020-01-01')], [self::claim('2018-01-01', '2020-01-01'), self::claim('2016-01-01', '2018-01-01')]), 'adjacent ([a,b) then [b,c)), any order');
        $this->assertTrue(self::covered([self::claim('2016-01-01')], [self::claim('2016-01-01', '2017-01-01'), self::claim('2017-01-01', '2019-06-01'), self::claim('2019-06-01')]), 'three, ending open');
    }

    public function test_a_gap_of_one_day_is_not_coverage(): void
    {
        $this->assertFalse(self::covered([self::claim('2016-01-01', '2020-01-01')], [self::claim('2016-01-01', '2018-01-01'), self::claim('2018-01-02', '2020-01-01')]));
    }

    public function test_starting_too_late_or_ending_too_early_is_not_coverage(): void
    {
        $this->assertFalse(self::covered([self::claim('2016-01-01', '2020-01-01')], [self::claim('2016-01-02', '2020-01-01')]), 'starts too late (the first day uncovered)');
        $this->assertFalse(self::covered([self::claim('2016-01-01', '2020-01-01')], [self::claim('2016-01-01', '2019-12-31')]), 'ends too early (the last day uncovered)');
        $this->assertTrue(self::covered([self::claim('2016-01-01', '2020-01-01')], [self::claim('2016-01-01', '2020-01-01')]), 'boundaries: to is exclusive on both');
    }

    public function test_an_open_ended_fact_needs_an_open_ended_replacement(): void
    {
        $this->assertFalse(self::covered([self::claim('2016-01-01')], [self::claim('2016-01-01', '9999-12-31')]), 'no finite end stands for "until further notice"');
        $this->assertTrue(self::covered([self::claim('2016-01-01')], [self::claim('2015-01-01')]));
        $this->assertTrue(self::covered([self::claim('2016-01-01', '2020-01-01')], [self::claim('2016-01-01')]), 'an open-ended replacement covers a finite fact');
    }

    public function test_every_flagged_claim_must_be_covered_in_its_own_slot(): void
    {
        $other = self::slot('6c5d6c5d-6c5d-4c5d-8c5d-6c5d6c5d6c5d');
        $flagged = [self::claim('2016-01-01'), self::claim('2016-01-01', null, $other)];

        $uncovered = CorrectionCoverage::uncovered($flagged, [self::claim('2016-01-01')]);
        $this->assertSame([$flagged[1]], $uncovered, 'all but one covered: the one is reported');
        $this->assertFalse(self::covered([self::claim('2016-01-01')], [self::claim('2016-01-01', null, $other)]), 'a claim in another slot replaces nothing');
        $this->assertTrue(self::covered($flagged, [self::claim('2016-01-01'), self::claim('2016-01-01', null, $other)]));
    }

    public function test_an_empty_replacement_covers_nothing(): void
    {
        $this->assertFalse(self::covered([self::claim('2016-01-01')], []));
    }

    public function test_a_correction_needs_an_open_finding_and_full_coverage(): void
    {
        $flagged = [self::claim('2016-01-01')];
        $finding = [9, GovernanceNotice::finding(5, ['m1'], 1)];
        $cases = [
            'open finding, full coverage' => [FindingLifecycle::of(5, [$finding], []), [self::claim('2016-01-01')], []],
            'open finding, partial' => [FindingLifecycle::of(5, [$finding], []), [self::claim('2017-01-01')], [GovernanceRefusal::CorrectionIncomplete]],
            'no finding at all' => [FindingLifecycle::of(5, [], []), [self::claim('2016-01-01')], [GovernanceRefusal::CorrectionWithoutOpenFinding]],
            'finding closed' => [FindingLifecycle::of(5, [$finding, [10, GovernanceNotice::closure(9, 'reversal:m1', 2)]], []), [self::claim('2016-01-01')], [GovernanceRefusal::CorrectionWithoutOpenFinding]],
            'finding confirmed' => [FindingLifecycle::of(5, [$finding, [10, GovernanceNotice::confirmation(9, PersonReference::fromString(md5('c')), 'd', 2)]], []), [self::claim('2016-01-01')], [GovernanceRefusal::CorrectionWithoutOpenFinding]],
            'already corrected' => [FindingLifecycle::of(5, [$finding], [11]), [self::claim('2016-01-01')], [GovernanceRefusal::CorrectionWithoutOpenFinding]],
        ];
        foreach ($cases as $why => [$lifecycle, $replacements, $refusals]) {
            $this->assertSame($refusals, CorrectionCoverage::refusals($lifecycle, $flagged, $replacements), $why);
        }
    }
}
