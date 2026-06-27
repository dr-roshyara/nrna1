<?php

namespace Tests\Feature\Contribution;

use App\Models\Contribution;
use App\Models\Organisation;
use App\Models\PointsLedger;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\ContributionPointsService;
use App\Services\GaneshStandardFormula;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ContributionPointsServiceTest extends TestCase
{
    use DatabaseTransactions;

    private ContributionPointsService $service;
    private Organisation $organisation;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ContributionPointsService(new GaneshStandardFormula());

        $this->organisation = Organisation::withoutGlobalScopes()->where('is_default', true)->first()
            ?? Organisation::factory()->create(['type' => 'platform', 'is_default' => true]);

        $this->user = User::factory()->create([
            'organisation_id' => $this->organisation->id,
        ]);

        session(['current_organisation_id' => $this->organisation->id]);
    }

    /** @test */
    public function it_calculates_points_and_writes_to_ledger()
    {
        $contribution = Contribution::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $this->user->id,
            'created_by'      => $this->user->id,
            'title'           => 'Community tutoring',
            'description'     => 'Weekly maths tutoring for kids',
            'track'           => 'micro',
            'status'          => 'approved',
            'effort_units'    => 3,
            'proof_type'      => 'self_report',
            'team_skills'     => ['teaching'],
            'is_recurring'    => false,
            'outcome_bonus'   => 0,
        ]);

        $points = $this->service->awardPoints($contribution);

        $this->assertEquals(15, $points);

        $this->assertDatabaseHas('points_ledger', [
            'user_id'         => $this->user->id,
            'contribution_id' => $contribution->id,
            'points'          => 15,
            'action'          => 'earned',
        ]);
    }

    /** @test */
    public function weekly_cap_is_enforced_across_multiple_contributions()
    {
        // Simulate 60 points already earned this week via ledger
        PointsLedger::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $this->user->id,
            'contribution_id' => Contribution::factory()->create([
                'organisation_id' => $this->organisation->id,
                'user_id'         => $this->user->id,
                'created_by'      => $this->user->id,
            ])->id,
            'points'          => 60,
            'action'          => 'earned',
            'created_by'      => $this->user->id,
        ]);

        // New micro contribution that would earn 50 raw points (10 units * 10 * 0.5)
        $contribution = Contribution::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $this->user->id,
            'created_by'      => $this->user->id,
            'title'           => 'Another micro task',
            'description'     => 'Quick help session',
            'track'           => 'micro',
            'status'          => 'approved',
            'effort_units'    => 10,
            'proof_type'      => 'self_report',
            'team_skills'     => [],
            'is_recurring'    => false,
            'outcome_bonus'   => 0,
        ]);

        $points = $this->service->awardPoints($contribution);

        // 100 cap - 60 already earned = 40 remaining
        $this->assertEquals(40, $points);
    }

    /** @test */
    public function it_returns_zero_points_when_weekly_cap_already_reached()
    {
        PointsLedger::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $this->user->id,
            'contribution_id' => Contribution::factory()->create([
                'organisation_id' => $this->organisation->id,
                'user_id'         => $this->user->id,
                'created_by'      => $this->user->id,
            ])->id,
            'points'          => 100,
            'action'          => 'earned',
            'created_by'      => $this->user->id,
        ]);

        $contribution = Contribution::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $this->user->id,
            'created_by'      => $this->user->id,
            'title'           => 'Over-cap contribution',
            'description'     => 'This should earn 0 points',
            'track'           => 'micro',
            'status'          => 'approved',
            'effort_units'    => 3,
            'proof_type'      => 'self_report',
            'team_skills'     => [],
            'is_recurring'    => false,
            'outcome_bonus'   => 0,
        ]);

        $points = $this->service->awardPoints($contribution);

        $this->assertEquals(0, $points);

        // Still writes a zero ledger entry for audit completeness
        $this->assertDatabaseHas('points_ledger', [
            'user_id'         => $this->user->id,
            'contribution_id' => $contribution->id,
            'points'          => 0,
            'action'          => 'earned',
        ]);
    }

    /** @test */
    public function non_micro_tracks_are_not_subject_to_weekly_cap()
    {
        PointsLedger::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $this->user->id,
            'contribution_id' => Contribution::factory()->create([
                'organisation_id' => $this->organisation->id,
                'user_id'         => $this->user->id,
                'created_by'      => $this->user->id,
            ])->id,
            'points'          => 100,
            'action'          => 'earned',
            'created_by'      => $this->user->id,
        ]);

        $contribution = Contribution::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $this->user->id,
            'created_by'      => $this->user->id,
            'title'           => 'Major project',
            'description'     => 'Community hall renovation',
            'track'           => 'standard',
            'status'          => 'approved',
            'effort_units'    => 10,
            'proof_type'      => 'photo',
            'team_skills'     => ['engineering', 'design', 'community'],
            'is_recurring'    => false,
            'outcome_bonus'   => 0,
        ]);

        $points = $this->service->awardPoints($contribution);

        // Standard track: (100 + 50) * 1.5 * 0.7 = 157 — no cap
        $this->assertEquals(157, $points);
    }

    /** @test */
    public function get_weekly_points_sums_only_current_week_earned_entries()
    {
        // Old ledger entry (last week — should NOT count)
        // Use DB::table directly so we can set created_at to last week
        // (Eloquent ignores created_at in create() since it's not in $fillable)
        $contribution = Contribution::factory()->create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $this->user->id,
            'created_by'      => $this->user->id,
        ]);
        DB::table('points_ledger')->insert([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->organisation->id,
            'user_id'         => $this->user->id,
            'contribution_id' => $contribution->id,
            'points'          => 50,
            'action'          => 'earned',
            'created_by'      => $this->user->id,
            'created_at'      => now()->subWeek()->toDateTimeString(),
            'updated_at'      => now()->subWeek()->toDateTimeString(),
        ]);

        $weeklyPoints = $this->service->getWeeklyPoints($this->user->id, $this->organisation->id);

        $this->assertEquals(0, $weeklyPoints);
    }
}
