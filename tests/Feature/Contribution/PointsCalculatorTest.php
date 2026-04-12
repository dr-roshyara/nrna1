<?php

namespace Tests\Feature\Contribution;

use App\Services\GaneshStandardFormula;
use PHPUnit\Framework\TestCase;

class PointsCalculatorTest extends TestCase
{
    private GaneshStandardFormula $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new GaneshStandardFormula();
    }

    /** @test */
    public function micro_track_contribution_calculates_correctly()
    {
        // A grandmother teaching her grandchild (micro, self-report)
        $points = $this->calculator->calculate([
            'track' => 'micro',
            'effort_units' => 3,  // 3 hours
            'proof_type' => 'self_report',
            'is_recurring' => false,
            'team_skills' => ['teaching'],
        ], $weeklyPoints = 0);

        // Base: 3 * 10 = 30, Tier bonus: 0 (micro has no tier bonus), Multipliers: 0.5x = 15
        $this->assertEquals(15, $points);
        $this->assertLessThanOrEqual(30, $points);
    }

    /** @test */
    public function weekly_cap_enforces_on_micro_track()
    {
        // First contribution: already at 0 weekly points, earn 15
        $points1 = $this->calculator->calculate([
            'track' => 'micro',
            'effort_units' => 3,
            'proof_type' => 'self_report',
        ], $weeklyPoints = 0);

        // Second contribution: 15 already earned, earn 15 more (total 30)
        $points2 = $this->calculator->calculate([
            'track' => 'micro',
            'effort_units' => 3,
            'proof_type' => 'self_report',
        ], $weeklyPoints = 30);

        // Third contribution: 60 already earned, would earn 50 alone but cap is 100
        // Remaining capacity: 100 - 60 = 40
        $points3 = $this->calculator->calculate([
            'track' => 'micro',
            'effort_units' => 10,  // Would be 50 points alone (10 * 10 * 0.5)
            'proof_type' => 'self_report',
        ], $weeklyPoints = 60);

        // Cap is 100, so remaining capacity is 40
        $this->assertEquals(40, $points3);
        $this->assertLessThanOrEqual(100, $points1 + $points2 + $points3);
    }

    /** @test */
    public function standard_track_with_photo_verification()
    {
        $points = $this->calculator->calculate([
            'track' => 'standard',
            'effort_units' => 10,
            'proof_type' => 'photo',
            'is_recurring' => false,
            'team_skills' => ['coding', 'design', 'marketing'],
        ], $weeklyPoints = 0);

        // Base linear: 10 * 10 = 100
        // Tier bonus: effort_units=10 is max of standard (31-200 pts range), tier bonus = 50
        // Total before multipliers: 100 + 50 = 150
        // Synergy (3 unique skills = cross-pollination): 1.5x
        // Verification (photo): 0.7x
        // 150 * 1.5 * 0.7 = 157.5 → floor = 157
        $this->assertEquals(157, $points);
    }

    /** @test */
    public function major_track_with_institutional_verification_and_recurring_bonus()
    {
        $points = $this->calculator->calculate([
            'track' => 'major',
            'effort_units' => 40,
            'proof_type' => 'institutional',
            'is_recurring' => true,
            'team_skills' => ['engineering', 'project_management', 'community_outreach'],
            'outcome_bonus' => 100,
        ], $weeklyPoints = 0);

        $this->assertGreaterThan(500, $points);
    }

    /** @test */
    public function synergy_multiplier_rewards_unique_skills()
    {
        $sameSkills       = $this->calculator->calculateSkillDiversityBonus(['coder', 'coder', 'coder']);
        $mixedSkills      = $this->calculator->calculateSkillDiversityBonus(['coder', 'doctor', 'teacher']);
        $crossPollination = $this->calculator->calculateSkillDiversityBonus(['engineer', 'marketer', 'community_organizer', 'designer']);

        $this->assertEquals(1.0, $sameSkills);
        $this->assertEquals(1.5, $mixedSkills);      // 3 unique skills = cross-pollination
        $this->assertEquals(1.5, $crossPollination); // 4 unique skills = cross-pollination
    }

    /** @test */
    public function multiplier_cap_prevents_explosion()
    {
        // 3 skills (1.5×) + institutional (1.2×) + recurring (1.2×) = 2.16× raw → capped at 2.0×
        $points = $this->calculator->calculate([
            'track'        => 'standard',
            'effort_units' => 10,
            'proof_type'   => 'institutional',
            'team_skills'  => ['coding', 'design', 'marketing'],
            'is_recurring' => true,
        ], $weeklyPoints = 0);

        // Without cap: floor(150 × 2.16) = 324
        // With 2.0× cap: floor(150 × 2.0) = 300
        $this->assertEquals(300, $points);
    }

    /** @test */
    public function diminishing_returns_applies_after_20_hours()
    {
        // Standard track, self-report only, single skill — isolates the effort curve.
        // NOTE: assertLessThan($points20 * 2, $points40) is not sufficient because the flat
        // tier bonus (+50) already prevents exact doubling even with linear effort.
        // Instead we assert the exact value that only the log-based formula can produce.
        $points40 = $this->calculator->calculate([
            'track'        => 'standard',
            'effort_units' => 40,
            'proof_type'   => 'self_report',
            'team_skills'  => ['teaching'],
            'is_recurring' => false,
        ], $weeklyPoints = 0);

        // Linear formula (current):      floor((400 + 50) × 0.5) = 225
        // Diminishing-returns formula:   effectiveEffort = 20 + log(21) × 5 ≈ 35.22
        //                                floor((352.2 + 50) × 0.5) = floor(201.1) = 201
        $this->assertEquals(201, $points40);
    }

    /** @test */
    public function community_attestation_provides_grassroots_alternative()
    {
        // standard, 10 h, 1 skill (no synergy bonus), community_attestation = 1.1×
        $points = $this->calculator->calculate([
            'track'        => 'standard',
            'effort_units' => 10,
            'proof_type'   => 'community_attestation',
            'team_skills'  => ['teaching'],
            'is_recurring' => false,
        ], $weeklyPoints = 0);

        // base = 100, tier = +50, subtotal = 150; multiplier = 1.0 × 1.1 × 1.0 = 1.1
        // floor(150 × 1.1) = 165
        $this->assertEquals(165, $points);
    }

    /** @test */
    public function points_are_stored_as_integers()
    {
        $points = $this->calculator->calculate([
            'track' => 'standard',
            'effort_units' => 3,  // 30 linear points
            'proof_type' => 'third_party',  // 1.0x
            'team_skills' => ['design', 'coding'],  // 1.2x mixed
        ], $weeklyPoints = 0);

        // Base: 3 * 10 = 30, no tier bonus at this level
        // Synergy (2 unique skills = mixed): 1.2x
        // Verification (third_party): 1.0x
        // 30 * 1.2 * 1.0 = 36.0 → 36
        $this->assertIsInt($points);
        $this->assertEquals(36, $points);
    }
}
