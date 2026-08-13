<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Services\ConstitutionalTransitionGuard;
use App\Domain\Election\Constitution\ElectionConstitution;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use App\Exceptions\InvalidTransitionException;
use App\Models\Election;
use Tests\TestCase;

/**
 * Phase 1: Constitutional Guard Layer
 * Test: ConstitutionalTransitionGuard Enforcement
 *
 * RED tests for the hard gate that enforces state transition rules.
 * Guard is mandatory before ANY state mutation.
 */
class ConstitutionalTransitionGuardTest extends TestCase
{
    private ConstitutionalTransitionGuard $guard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guard = new ConstitutionalTransitionGuard();
    }

    /**
     * Test: Guard allows action if state is in allowed_states
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_allows_action_if_state_is_allowed(): void
    {
        $election = Election::factory()->create([
            'voting_starts_at' => now()->addDays(1),
            'voting_ends_at' => now()->addDays(2),
            'timezone' => 'UTC',  // Required precondition for open_voting
        ]);
        // EM-VOT-002 (adopted): open_voting requires at least one approved
        // candidate — part of a permissible open_voting's domain preconditions.
        $post = \App\Models\Post::factory()->create([
            'election_id'     => $election->id,
            'organisation_id' => $election->organisation_id,
        ]);
        \App\Models\Candidacy::factory()->create([
            'post_id'         => $post->id,
            'organisation_id' => $election->organisation_id,
            'user_id'         => \App\Models\User::factory()->create()->id,
            'status'          => 'approved',
        ]);
        $user = \App\Models\User::factory()->create();

        // Create and assign chief role so guard allows the action
        \Spatie\Permission\Models\Role::create(['name' => 'chief', 'guard_name' => 'web']);
        $user->assignRole('chief');
        $this->actingAs($user);

        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::ReadyForVoting,
            canEdit: false,
            canVote: false,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: true,
            blockedReason: null,
            allowedActions: ['open_voting'],
        );

        // Should not throw
        $this->guard->assertAllowed($election, 'open_voting', $snapshot);
        $this->assertTrue(true);
    }

    /**
     * Test: Guard throws if state is not allowed
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_throws_if_state_not_allowed(): void
    {
        $election = Election::factory()->create();
        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Draft,
            canEdit: true,
            canVote: false,
            canManageVoters: true,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: 'Setup not started',
            allowedActions: [],
        );

        $this->expectException(InvalidTransitionException::class);
        $this->guard->assertAllowed($election, 'open_voting', $snapshot);
    }

    /**
     * Test: Guard throws if action is not defined
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_throws_if_action_undefined(): void
    {
        $election = Election::factory()->create();
        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::ReadyForVoting,
            canEdit: false,
            canVote: false,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: true,
            blockedReason: null,
            allowedActions: ['open_voting'],
        );

        $this->expectException(InvalidTransitionException::class);
        $this->guard->assertAllowed($election, 'invalid_action', $snapshot);
    }

    /**
     * Test: Guard verifies user has required role
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_verifies_user_role(): void
    {
        $election = Election::factory()->create();
        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::ReadyForVoting,
            canEdit: false,
            canVote: false,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: true,
            blockedReason: null,
            allowedActions: ['open_voting'],
        );

        // Create a user without chief role
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $this->expectException(InvalidTransitionException::class);
        $this->guard->assertAllowed($election, 'open_voting', $snapshot);
    }

    /**
     * Test: Only chief can open voting (role enforcement)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function only_chief_can_open_voting(): void
    {
        $allowedRoles = ElectionConstitution::getAllowedRolesForAction('open_voting');

        $this->assertContains('chief', $allowedRoles);
        $this->assertCount(1, $allowedRoles);
    }

    /**
     * Test: Chief and deputy can administer (role enforcement)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function chief_and_deputy_can_administer(): void
    {
        $allowedRoles = ElectionConstitution::getAllowedRolesForAction('complete_administration');

        $this->assertContains('chief', $allowedRoles);
        $this->assertContains('deputy', $allowedRoles);
    }

    /**
     * Test: Guard is injectable
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_is_injectable(): void
    {
        $resolved = app(ConstitutionalTransitionGuard::class);

        $this->assertInstanceOf(ConstitutionalTransitionGuard::class, $resolved);
    }

    /**
     * Test: Guard checks preconditions
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_validates_preconditions(): void
    {
        $preconditions = ElectionConstitution::getPreconditionsForAction('complete_administration');

        $this->assertNotEmpty($preconditions);
        $this->assertContains('has_posts', $preconditions);
        $this->assertContains('has_voters', $preconditions);
        $this->assertContains('has_committee_members', $preconditions);
    }

    /**
     * RED: System actions should be allowed without authenticated user
     *
     * auto_submit requires 'system' role and should work without Auth::user()
     * because it's automatically triggered, not user-triggered.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function system_actions_allowed_without_authenticated_user(): void
    {
        $org = \App\Models\Organisation::firstOrCreate(
            ['name' => 'Test Org'],
            ['slug' => 'test-org', 'id' => 9999]
        );

        $election = Election::factory()
            ->forOrganisation($org)
            ->create([
                'expected_voter_count' => 30,  // Free plan
                'submitted_for_approval_at' => null,
                'approved_at' => null,
                'administration_completed' => false,
            ]);

        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Draft,
            canEdit: true,
            canVote: false,
            canManageVoters: true,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: null,
            allowedActions: ['submit_for_approval', 'auto_submit'],
        );

        // Ensure no user is authenticated
        \Illuminate\Support\Facades\Auth::logout();
        $this->assertNull(\Illuminate\Support\Facades\Auth::user());

        // auto_submit should succeed even without Auth::user()
        // because 'system' role is valid for automatic actions
        $this->guard->assertAllowed($election, 'auto_submit', $snapshot);
        $this->assertTrue(true);
    }

    /**
     * PHASE B: OPERATIONAL SUSPENSION OVERLAY
     * RED test: Suspend is allowed from VotingActive state
     *
     * Suspension should be allowed from any non-archived state.
     * VotingActive is the most common case where suspension is needed.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function suspend_is_allowed_from_voting_active(): void
    {
        $election = Election::factory()->create();
        $user = \App\Models\User::factory()->create();

        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'platform_admin', 'guard_name' => 'web']);
        $user->assignRole('platform_admin');
        $this->actingAs($user);

        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::VotingActive,
            canEdit: false,
            canVote: true,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: false,
            isLocked: true,
            blockedReason: null,
            allowedActions: ['suspend'],  // suspend should be available
        );

        // Should not throw
        $this->guard->assertAllowed($election, 'suspend', $snapshot);
        $this->assertTrue(true);
    }

    /**
     * RED test: Suspend is not allowed from Archived state
     *
     * Suspension is an operational governance overlay.
     * Archived elections are terminal — no operational changes allowed.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function suspend_is_not_allowed_from_archived(): void
    {
        $election = Election::factory()->create();
        $user = \App\Models\User::factory()->create();

        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'platform_admin', 'guard_name' => 'web']);
        $user->assignRole('platform_admin');
        $this->actingAs($user);

        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Archived,
            canEdit: false,
            canVote: false,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: false,
            isLocked: true,
            blockedReason: 'Election is archived',
            allowedActions: [],  // no operations allowed
        );

        // Should throw
        $this->expectException(InvalidTransitionException::class);
        $this->guard->assertAllowed($election, 'suspend', $snapshot);
    }

    /**
     * RED test: Resume is allowed from Suspended state
     *
     * Resume clears suspension metadata and allows engine to re-derive
     * lifecycle state from current constitutional facts.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function resume_is_allowed_from_suspended(): void
    {
        $election = Election::factory()->create([
            'suspended_at' => now()->subHours(2),
        ]);
        $user = \App\Models\User::factory()->create();

        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'platform_admin', 'guard_name' => 'web']);
        $user->assignRole('platform_admin');
        $this->actingAs($user);

        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Suspended,
            canEdit: false,
            canVote: false,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: false,
            isLocked: true,
            blockedReason: 'Election is suspended',
            allowedActions: ['resume'],  // only resume is allowed
        );

        // Should not throw
        $this->guard->assertAllowed($election, 'resume', $snapshot);
        $this->assertTrue(true);
    }

    /**
     * RED test: Resume is not allowed from non-suspended states
     *
     * Resume can only be called on suspended elections.
     * Resume has no meaning if election is not suspended.
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function resume_is_not_allowed_from_draft(): void
    {
        $election = Election::factory()->create();
        $user = \App\Models\User::factory()->create();

        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'platform_admin', 'guard_name' => 'web']);
        $user->assignRole('platform_admin');
        $this->actingAs($user);

        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Draft,
            canEdit: true,
            canVote: false,
            canManageVoters: true,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: null,
            allowedActions: ['submit_for_approval'],
        );

        // Should throw
        $this->expectException(InvalidTransitionException::class);
        $this->guard->assertAllowed($election, 'resume', $snapshot);
    }
}
