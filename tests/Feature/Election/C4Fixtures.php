<?php

namespace Tests\Feature\Election;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterVerification;

/**
 * Shared fixtures for the PBDIGIT-213 C4 feature tests (no behaviour of its own).
 * Never $org->users()->attach() for factory users (UserFactory creates the role row).
 */
trait C4Fixtures
{
    protected Organisation $org;

    protected function makeOrganisation(bool $fullMembership = false): void
    {
        Election::resetPlatformOrgCache();
        $this->org = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => $fullMembership]);
        session(['current_organisation_id' => $this->org->id]);
    }

    protected function officer(Election $election, string $role, ?string $electionId = 'same', ?string $name = null): User
    {
        $user = User::factory()->create(array_filter([
            'email_verified_at' => now(),
            'organisation_id'   => $this->org->id,
            'name'              => $name,
        ]));

        ElectionOfficer::create([
            'user_id'         => $user->id,
            'election_id'     => $electionId === 'same' ? $election->id : $electionId,
            'organisation_id' => $this->org->id,
            'role'            => $role,
            'status'          => 'active',
            'appointed_by'    => $user->id,
            'appointed_at'    => now(),
            'accepted_at'     => now(),
        ]);

        return $user;
    }

    protected function registeredVoter(Election $election, string $status = 'active'): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);

        ElectionMembership::create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $election->id,
            'role'            => 'voter',
            'status'          => $status,
        ]);

        return $user;
    }

    protected function evidence(User $voter, Election $election, User $verifier, string $status = 'active', ?string $reason = null): VoterVerification
    {
        return VoterVerification::create([
            'election_id'       => $election->id,
            'user_id'           => $voter->id,
            'organisation_id'   => $this->org->id,
            'verified_by'       => $verifier->id,
            'verified_at'       => now()->subDay(),
            'status'            => $status,
            'verifier_role'     => 'ELECTION_CHIEF',
            'method'            => 'officer_attestation',
            'revoked_by'        => $status === 'revoked' ? $verifier->id : null,
            'revoked_at'        => $status === 'revoked' ? now() : null,
            'revocation_reason' => $reason,
        ]);
    }

    protected function requireIdentityVerification(Election $election): void
    {
        $election->update(['voter_admission_policy' => ['requires' => ['OFFICER_IDENTITY_VERIFICATION']]]);
    }

    /** Move an unlocked election into VotingActive by its constitutional facts (policy untouched). */
    protected function openVoting(Election $election): Election
    {
        $election->update([
            'approved_at'                 => now()->subDays(2),
            'administration_completed'    => true,
            'administration_completed_at' => now()->subDay(),
            'nomination_completed'        => true,
            'nomination_completed_at'     => now()->subHours(23),
            'voting_starts_at'            => now()->subHour(),
            'voting_ends_at'              => now()->addHour(),
            'voting_locked'               => true,
        ]);
        $post = \App\Models\Post::factory()->create([
            'election_id' => $election->id, 'organisation_id' => $this->org->id, 'name' => 'Position', 'is_national_wide' => true,
        ]);
        \App\Models\Candidacy::factory()->create([
            'organisation_id' => $this->org->id,
            'post_id'         => $post->id,
            'user_id'         => User::factory()->create(['organisation_id' => $this->org->id])->id,
            'status'          => 'approved',
        ]);

        $election = $election->fresh();
        $this->assertSame(ElectionLifecycleState::VotingActive, ElectionLifecycle::of($election)->state(), 'Precondition: voting opened');

        return $election;
    }

    protected function voterRoute(string $name, Election $election, array $extra = []): string
    {
        return route($name, array_merge(['organisation' => $this->org->slug, 'election' => $election->slug], $extra));
    }
}
