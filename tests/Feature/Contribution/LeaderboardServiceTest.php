<?php

namespace Tests\Feature\Contribution;

use App\Models\Contribution;
use App\Models\Organisation;
use App\Models\PointsLedger;
use App\Models\User;
use App\Services\LeaderboardService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class LeaderboardServiceTest extends TestCase
{
    use DatabaseTransactions;

    private LeaderboardService $service;
    private Organisation $organisation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new LeaderboardService();

        $this->organisation = Organisation::withoutGlobalScopes()
            ->where('is_default', true)->first()
            ?? Organisation::factory()->create(['type' => 'platform', 'is_default' => true]);

        session(['current_organisation_id' => $this->organisation->id]);
    }

    /** @test */
    public function public_users_appear_with_real_name()
    {
        $user = User::factory()->create([
            'organisation_id'        => $this->organisation->id,
            'name'                   => 'Dr. Sharma',
            'leaderboard_visibility' => 'public',
        ]);

        $this->seedPoints($user, 80);

        $board = $this->service->get($this->organisation->id);

        $entry = $board->firstWhere('user_id', $user->id);
        $this->assertNotNull($entry);
        $this->assertEquals('Dr. Sharma', $entry['display_name']);
    }

    /** @test */
    public function anonymous_users_appear_as_contributor_number()
    {
        $user = User::factory()->create([
            'organisation_id'        => $this->organisation->id,
            'name'                   => 'Mrs. Kaur',
            'leaderboard_visibility' => 'anonymous',
        ]);

        $this->seedPoints($user, 50);

        $board = $this->service->get($this->organisation->id);

        $entry = $board->firstWhere('user_id', $user->id);
        $this->assertNotNull($entry);
        $this->assertStringStartsWith('Contributor #', $entry['display_name']);
        $this->assertStringNotContainsString('Kaur', $entry['display_name']);
    }

    /** @test */
    public function private_users_are_excluded_from_leaderboard()
    {
        $user = User::factory()->create([
            'organisation_id'        => $this->organisation->id,
            'name'                   => 'Mr. Tamang',
            'leaderboard_visibility' => 'private',
        ]);

        $this->seedPoints($user, 90);

        $board = $this->service->get($this->organisation->id);

        $this->assertNull($board->firstWhere('user_id', $user->id));
    }

    /** @test */
    public function leaderboard_is_sorted_by_total_points_descending()
    {
        $low  = User::factory()->create(['organisation_id' => $this->organisation->id, 'leaderboard_visibility' => 'public']);
        $high = User::factory()->create(['organisation_id' => $this->organisation->id, 'leaderboard_visibility' => 'public']);

        $this->seedPoints($low, 30);
        $this->seedPoints($high, 120);

        $board = $this->service->get($this->organisation->id);

        $ids = $board->pluck('user_id')->all();
        $highIndex = array_search($high->id, $ids);
        $lowIndex  = array_search($low->id, $ids);

        $this->assertLessThan($lowIndex, $highIndex, 'Higher points should appear first');
    }

    /** @test */
    public function leaderboard_is_scoped_to_organisation()
    {
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);
        $outsider = User::factory()->create([
            'organisation_id'        => $otherOrg->id,
            'leaderboard_visibility' => 'public',
        ]);

        // Give points in the other org's ledger
        $contribution = Contribution::factory()->create([
            'organisation_id' => $otherOrg->id,
            'user_id'         => $outsider->id,
            'created_by'      => $outsider->id,
        ]);
        DB::table('points_ledger')->insert([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $otherOrg->id,
            'user_id'         => $outsider->id,
            'contribution_id' => $contribution->id,
            'points'          => 200,
            'action'          => 'earned',
            'created_by'      => $outsider->id,
            'created_at'      => now()->toDateTimeString(),
            'updated_at'      => now()->toDateTimeString(),
        ]);

        $board = $this->service->get($this->organisation->id);

        $this->assertNull($board->firstWhere('user_id', $outsider->id));
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function seedPoints(User $user, int $points): void
    {
        $contribution = Contribution::factory()->create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $user->id,
            'created_by'      => $user->id,
        ]);

        PointsLedger::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $user->id,
            'contribution_id' => $contribution->id,
            'points'          => $points,
            'action'          => 'earned',
            'created_by'      => $user->id,
        ]);
    }
}
