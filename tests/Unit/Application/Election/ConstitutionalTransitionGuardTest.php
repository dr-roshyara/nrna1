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
}
