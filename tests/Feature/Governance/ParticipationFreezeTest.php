<?php

namespace Tests\Feature\Governance;

use App\Models\User;
use App\Models\Organisation;
use App\Models\Election;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use Tests\TestCase;

class ParticipationFreezeTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_elections_exist_in_locked_states()
    {
        $org = Organisation::create([
            'name' => 'Test Org',
            'slug' => 'test-org',
            'uses_full_membership' => true,
        ]);

        // Create elections in various locked states
        Election::create([
            'organisation_id' => $org->id,
            'name' => 'Setup Admin Election',
            'slug' => 'setup-admin',
            'type' => 'real',
            'state' => ElectionLifecycleState::SetupAdministration->value,
        ]);

        Election::create([
            'organisation_id' => $org->id,
            'name' => 'Voting Active Election',
            'slug' => 'voting-active',
            'type' => 'real',
            'state' => ElectionLifecycleState::VotingActive->value,
        ]);

        // Verify elections exist in locked states
        $lockedExists = Election::withoutGlobalScopes()
            ->where('organisation_id', $org->id)
            ->get()
            ->contains(fn($e) => ElectionLifecycleState::tryFrom($e->state) &&
                match(ElectionLifecycleState::tryFrom($e->state)) {
                    ElectionLifecycleState::Draft,
                    ElectionLifecycleState::SubmittedForApproval,
                    ElectionLifecycleState::Approved,
                    ElectionLifecycleState::Rejected,
                    ElectionLifecycleState::Archived => false,
                    default => true,
                });

        $this->assertTrue($lockedExists);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_elections_in_draft_are_not_locked()
    {
        $org = Organisation::create([
            'name' => 'Test Org',
            'slug' => 'test-org-2',
            'uses_full_membership' => true,
        ]);

        Election::create([
            'organisation_id' => $org->id,
            'name' => 'Draft Election',
            'slug' => 'draft-election',
            'type' => 'real',
            'state' => ElectionLifecycleState::Draft->value,
        ]);

        $lockedExists = Election::withoutGlobalScopes()
            ->where('organisation_id', $org->id)
            ->get()
            ->contains(fn($e) => ElectionLifecycleState::tryFrom($e->state) &&
                match(ElectionLifecycleState::tryFrom($e->state)) {
                    ElectionLifecycleState::Draft,
                    ElectionLifecycleState::SubmittedForApproval,
                    ElectionLifecycleState::Approved,
                    ElectionLifecycleState::Rejected,
                    ElectionLifecycleState::Archived => false,
                    default => true,
                });

        $this->assertFalse($lockedExists);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_no_elections_means_not_locked()
    {
        $org = Organisation::create([
            'name' => 'Test Org',
            'slug' => 'test-org-3',
            'uses_full_membership' => true,
        ]);

        $lockedExists = Election::withoutGlobalScopes()
            ->where('organisation_id', $org->id)
            ->get()
            ->contains(fn($e) => ElectionLifecycleState::tryFrom($e->state) &&
                match(ElectionLifecycleState::tryFrom($e->state)) {
                    ElectionLifecycleState::Draft,
                    ElectionLifecycleState::SubmittedForApproval,
                    ElectionLifecycleState::Approved,
                    ElectionLifecycleState::Rejected,
                    ElectionLifecycleState::Archived => false,
                    default => true,
                });

        $this->assertFalse($lockedExists);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_archived_elections_are_not_locked()
    {
        $org = Organisation::create([
            'name' => 'Test Org',
            'slug' => 'test-org-4',
            'uses_full_membership' => true,
        ]);

        Election::create([
            'organisation_id' => $org->id,
            'name' => 'Archived Election',
            'slug' => 'archived-election',
            'type' => 'real',
            'state' => ElectionLifecycleState::Archived->value,
        ]);

        $lockedExists = Election::withoutGlobalScopes()
            ->where('organisation_id', $org->id)
            ->get()
            ->contains(fn($e) => ElectionLifecycleState::tryFrom($e->state) &&
                match(ElectionLifecycleState::tryFrom($e->state)) {
                    ElectionLifecycleState::Draft,
                    ElectionLifecycleState::SubmittedForApproval,
                    ElectionLifecycleState::Approved,
                    ElectionLifecycleState::Rejected,
                    ElectionLifecycleState::Archived => false,
                    default => true,
                });

        $this->assertFalse($lockedExists);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_is_participation_locked_returns_false_for_pre_setup_states()
    {
        $draftSnapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Draft,
            canEdit: true,
            canVote: false,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: null,
            allowedActions: []
        );

        $this->assertFalse($draftSnapshot->isParticipationLocked());

        $approvedSnapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Approved,
            canEdit: false,
            canVote: false,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: null,
            allowedActions: []
        );

        $this->assertFalse($approvedSnapshot->isParticipationLocked());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_is_participation_locked_returns_true_from_setup_administration()
    {
        $setupSnapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::SetupAdministration,
            canEdit: true,
            canVote: false,
            canManageVoters: true,
            canPublishResults: false,
            canEditTimeline: false,
            isLocked: false,
            blockedReason: null,
            allowedActions: []
        );

        $this->assertTrue($setupSnapshot->isParticipationLocked());

        $votingSnapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::VotingActive,
            canEdit: false,
            canVote: true,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: false,
            isLocked: false,
            blockedReason: null,
            allowedActions: []
        );

        $this->assertTrue($votingSnapshot->isParticipationLocked());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_is_participation_locked_returns_true_for_ready_for_voting()
    {
        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::ReadyForVoting,
            canEdit: false,
            canVote: false,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: false,
            isLocked: false,
            blockedReason: 'Voting not yet open',
            allowedActions: []
        );

        $this->assertTrue($snapshot->isParticipationLocked());
    }
}
