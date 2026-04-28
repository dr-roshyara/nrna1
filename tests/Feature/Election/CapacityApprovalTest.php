<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionStateTransition;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CapacityApprovalTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org   = Organisation::factory()->create();
        $this->owner = User::factory()->create();
        \App\Models\UserOrganisationRole::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->owner->id,
            'role'            => 'owner',
        ]);
    }

    private function draftElection(int $expectedVoters): Election
    {
        return Election::factory()->create([
            'organisation_id'      => $this->org->id,
            'state'                => 'draft',
            'expected_voter_count' => $expectedVoters,
        ]);
    }

    private function addVoters(Election $election, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $voter = User::factory()->create();
            \App\Models\UserOrganisationRole::create([
                'organisation_id' => $this->org->id,
                'user_id'         => $voter->id,
                'role'            => 'member',
            ]);
            ElectionMembership::create([
                'id'                => \Str::uuid(),
                'organisation_id'   => $this->org->id,
                'election_id'       => $election->id,
                'user_id'           => $voter->id,
                'role'              => 'voter',
                'status'            => 'active',
                'metadata'          => [],
                'has_voted'         => false,
                'suspension_status' => 'none',
            ]);
        }
    }

    /** @test */
    public function election_with_expected_40_voters_auto_approves(): void
    {
        $election = $this->draftElection(40);
        $election->submitForApproval($this->owner->id);
        $this->assertEquals('administration', $election->fresh()->state);
    }

    /** @test */
    public function election_with_expected_41_voters_goes_to_pending_approval(): void
    {
        $election = $this->draftElection(41);
        $election->submitForApproval($this->owner->id);
        $this->assertEquals('pending_approval', $election->fresh()->state);
    }

    /** @test */
    public function auto_submit_creates_single_system_audit_record(): void
    {
        $election = $this->draftElection(40);
        $election->submitForApproval($this->owner->id);

        $transitions = ElectionStateTransition::where('election_id', $election->id)->get();
        $this->assertCount(1, $transitions);
        $this->assertEquals('draft', $transitions->first()->from_state);
        $this->assertEquals('administration', $transitions->first()->to_state);
        $this->assertEquals('system', $transitions->first()->trigger);
    }

    /** @test */
    public function manual_submit_creates_manual_audit_record(): void
    {
        $election = $this->draftElection(41);
        $election->submitForApproval($this->owner->id);

        $transitions = ElectionStateTransition::where('election_id', $election->id)->get();
        $this->assertCount(1, $transitions);
        $this->assertEquals('draft', $transitions->first()->from_state);
        $this->assertEquals('pending_approval', $transitions->first()->to_state);
        $this->assertEquals('manual', $transitions->first()->trigger);
    }

    /** @test */
    public function requires_approval_returns_true_when_over_limit(): void
    {
        $election = $this->draftElection(41);
        $this->assertTrue($election->requiresApproval());
    }

    /** @test */
    public function requires_approval_returns_false_when_at_limit(): void
    {
        $election = $this->draftElection(40);
        $this->assertFalse($election->requiresApproval());
    }

    /** @test */
    public function cannot_import_more_voters_than_expected_count(): void
    {
        $election = $this->draftElection(10);
        $election->update(['state' => 'administration']);
        $this->addVoters($election, 10);

        // Sync voters_count to match actual membership count
        $election->update(['voters_count' => 10]);
        $freshElection = $election->fresh();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot add 1 voter');

        $freshElection->assertCanAcceptVoters(1);
    }

    /** @test */
    public function can_import_voters_up_to_expected_count(): void
    {
        $election = $this->draftElection(10);
        $election->update(['state' => 'administration']);
        $this->addVoters($election, 9);

        // Should NOT throw — 9 + 1 = 10 which equals expected
        $election->fresh()->assertCanAcceptVoters(1);
        $this->assertTrue(true);
    }
}
