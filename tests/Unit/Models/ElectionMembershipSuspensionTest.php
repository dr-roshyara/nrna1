<?php

namespace Tests\Unit\Models;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionMembershipSuspensionTest extends TestCase
{
    use RefreshDatabase;

    private ElectionMembership $membership;
    private User $proposer;
    private User $confirmer;

    protected function setUp(): void
    {
        parent::setUp();

        $org      = Organisation::factory()->create(['type' => 'tenant']);
        $election = Election::factory()->forOrganisation($org)->real()->create(['status' => 'active']);

        $this->proposer  = User::factory()->create(['name' => 'Alice', 'organisation_id' => $org->id]);
        $this->confirmer = User::factory()->create(['name' => 'Bob',   'organisation_id' => $org->id]);

        $voter = User::factory()->create(['organisation_id' => $org->id]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $voter->id,
            'organisation_id' => $org->id,
            'role'            => 'voter',
        ]);

        $this->membership = ElectionMembership::create([
            'user_id'         => $voter->id,
            'organisation_id' => $org->id,
            'election_id'     => $election->id,
            'role'            => 'voter',
            'status'          => 'active',
            'assigned_by'     => $this->proposer->id,
            'assigned_at'     => now(),
        ]);
    }

    public function test_propose_suspension_sets_fields(): void
    {
        $this->membership->proposeSuspension($this->proposer);
        $this->membership->refresh();

        $this->assertEquals('proposed', $this->membership->suspension_status);
        $this->assertEquals('Alice', $this->membership->suspension_proposed_by);
        $this->assertNotNull($this->membership->suspension_proposed_at);
        $this->assertEquals('active', $this->membership->status);
    }

    public function test_confirm_suspension_sets_inactive(): void
    {
        $this->membership->proposeSuspension($this->proposer);
        $this->membership->confirmSuspension($this->confirmer);
        $this->membership->refresh();

        $this->assertEquals('inactive', $this->membership->status);
        $this->assertEquals('confirmed', $this->membership->suspension_status);
    }

    public function test_cancel_suspension_proposal_resets_fields(): void
    {
        $this->membership->proposeSuspension($this->proposer);
        $this->membership->cancelSuspensionProposal();
        $this->membership->refresh();

        $this->assertEquals('none', $this->membership->suspension_status);
        $this->assertNull($this->membership->suspension_proposed_by);
        $this->assertNull($this->membership->suspension_proposed_at);
    }

    public function test_is_suspension_proposed_returns_true_when_proposed(): void
    {
        $this->membership->proposeSuspension($this->proposer);
        $this->assertTrue($this->membership->isSuspensionProposed());
    }

    public function test_is_suspension_proposed_returns_false_by_default(): void
    {
        $this->assertFalse($this->membership->isSuspensionProposed());
    }

    public function test_can_confirm_suspension_false_for_proposer(): void
    {
        $this->membership->proposeSuspension($this->proposer);
        $this->assertFalse($this->membership->canConfirmSuspension($this->proposer));
    }

    public function test_can_confirm_suspension_true_for_different_user(): void
    {
        $this->membership->proposeSuspension($this->proposer);
        $this->assertTrue($this->membership->canConfirmSuspension($this->confirmer));
    }
}
