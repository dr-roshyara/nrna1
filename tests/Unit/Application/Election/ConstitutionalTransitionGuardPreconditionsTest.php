<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Services\ConstitutionalTransitionGuard;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use App\Models\Election;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Phase 3.1.F: Guard Preconditions Performance & Correctness
 *
 * RED TEST: Verify preconditions use cached columns, not DB queries
 */
class ConstitutionalTransitionGuardPreconditionsTest extends TestCase
{
    private ConstitutionalTransitionGuard $guard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guard = new ConstitutionalTransitionGuard();
    }

    /**
     * RED: Preconditions should use cached count columns, not query DB
     *
     * complete_administration requires has_posts, has_voters, has_committee_members.
     * This runs 3 EXISTS queries. Should use cached columns instead.
     */
    public function test_complete_administration_preconditions_use_cached_columns(): void
    {
        $org = \App\Models\Organisation::firstOrCreate(
            ['name' => 'Query Test Org'],
            ['slug' => 'query-test-org', 'id' => 9997]
        );

        // Setup election with all preconditions met
        $election = Election::factory()
            ->forOrganisation($org)
            ->create([
                'posts_count' => 5,
                'voters_count' => 25,
                'election_committee_members_count' => 2,
                'expected_voter_count' => 25,
                'administration_completed' => false,
                'nomination_completed' => false,
            ]);

        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Setup,
            canEdit: true,
            canVote: false,
            canManageVoters: true,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: null,
            allowedActions: ['complete_administration'],
        );

        // Create a test user
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Count queries for precondition checks
        $queryCount = 0;
        DB::listen(function ($query) use (&$queryCount) {
            if (
                stripos($query->sql, 'posts') !== false &&
                (stripos($query->sql, 'count') !== false || stripos($query->sql, 'exists') !== false)
            ) {
                $queryCount++;
            }
        });

        try {
            $this->guard->assertAllowed($election, 'complete_administration', $snapshot);
        } catch (\Exception $e) {
            // May fail for role check, but we're testing precondition queries
        }

        // Guard should NOT query posts table - should use posts_count column
        $this->assertEquals(
            0,
            $queryCount,
            "Guard preconditions should use cached posts_count column "
            . "instead of querying posts table with EXISTS. "
            . "Complete_administration preconditions run on every Setup state check "
            . "and cause N+1 problems on election lists."
        );
    }

    /**
     * RED: has_committee_members should check election_officers, not election_memberships
     *
     * Committee members (chief, deputy) are in election_officers table,
     * not election_memberships. Or use cached election_committee_members_count.
     */
    public function test_has_committee_members_checks_correct_table(): void
    {
        $org = \App\Models\Organisation::firstOrCreate(
            ['name' => 'Committee Test Org'],
            ['slug' => 'committee-test-org', 'id' => 9996]
        );

        // Election with no committee members yet
        $election = Election::factory()
            ->forOrganisation($org)
            ->create([
                'posts_count' => 1,
                'voters_count' => 10,
                'candidates_count' => 5,
                'election_committee_members_count' => 0,  // No committee members
                'expected_voter_count' => 10,
            ]);

        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Setup,
            canEdit: true,
            canVote: false,
            canManageVoters: true,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: null,
            allowedActions: ['complete_administration'],
        );

        // complete_administration requires has_committee_members
        $this->expectException(\App\Exceptions\InvalidTransitionException::class);
        $this->guard->assertAllowed($election, 'complete_administration', $snapshot);
    }
}
